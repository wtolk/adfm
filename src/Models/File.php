<?php

namespace Wtolk\Adfm\Models;

use App\Helpers\Dev;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mimey\MimeTypes;

class File extends Model
{
    protected $table = 'adfm_attachments';

    /**
     * @var UploadedFile
     */
    protected $file;
    protected $storage;
    protected $time;
    protected $mimes;


    protected $disk = 'public';

    protected $group;

    protected $fillable = [
        'name',
        'mime',
        'hash',
        'extension',
        'original_name',
        'size',
        'path',
        'disk',
        'model_name',
        'model_id',
        'model_relation',
        'group',
        'user_id',
    ];

    public $model_relation = null;
    public $model_id = null;
    public $model_name = null;

    public static function make(UploadedFile $file)
    {
        $item = new self();
        $item->file = $file;
        $item->time = time();
        $item->mimes = new MimeTypes();
        $item->storage = Storage::disk($item->disk);
        return $item;
    }

    public function name(): string
    {
        return sha1($this->time.$this->file->getClientOriginalName());
    }

    public function path(): string
    {
        return date('Y/m/d', $this->time);
    }

    public function fullName(): string
    {
        return Str::finish($this->name(), '.').$this->extension();
    }

    public function extension(): string
    {
        $extension = $this->file->getClientOriginalExtension();

        return empty($extension)
            ? $this->mimes->getExtension($this->file->getClientMimeType())
            : $extension;
    }

    /**
     * Вычисляет хеш файла
     * @return string
     */
    public function hash(): string
    {
        return sha1_file($this->file->path());
    }

    /**
     * Returns the file mime type.
     *
     * @return string
     */
    public function mime(): string
    {
        return $this->mimes->getMimeType($this->extension())
            ?? $this->mimes->getMimeType($this->file->getClientMimeType())
            ?? 'unknown';
    }


    private function getMatchesHash()
    {
        return self::where('hash', $this->hash())
            ->where('disk', $this->disk)
            ->first();
    }

    public function upload()
    {
        $data = [
            'name'          => $this->name(),
            'mime'          => $this->mime(),
            'hash'          => $this->hash(),
            'extension'     => $this->extension(),
            'original_name' => $this->file->getClientOriginalName(),
            'size'          => $this->file->getSize(),
            'path'          => Str::finish($this->path(), '/'),
            'disk'          => $this->disk,
            'model_name'    => $this->model_name,
            'model_id'    => $this->model_id,
            'model_relation'    => $this->model_relation,
            'group'         => $this->group,
            'user_id'       => Auth::id(),
        ];
        $this->fill($data);
        $attachment = $this->getMatchesHash();
        if ($attachment === null) {
            Storage::putFileAs('files', $this->file, $this->file->getClientOriginalName());
            return $this->save();
        }

        $attachment = $attachment->replicate()->fill([
            'original_name' => $this->file->getClientOriginalName(),
            'sort'          => 0,
            'user_id'       => Auth::id(),
            'group'         => $this->group,
            'model_name'    => $this->model_name,
            'model_id'    => $this->model_id,
            'model_relation'    => $this->model_relation
        ]);
        $attachment->file = $this->file;
        $attachment->time = time();
        $attachment->mimes = new MimeTypes();
        $attachment->storage = Storage::disk($this->disk);
        $attachment->save();



        return $attachment;

    }

//    public function save(array $options = [])
//    {
//
////        Dev::dd($data); die;
//
//        ; // Рекурсия ебаная, надо исправлять , делай отдельный метод блять
////
////        if (! $this->storage->has($this->engine->path())) {
////            $this->storage->makeDirectory($this->engine->path());
////        }
////
//
////
//
//
////        $this->storage->putFileAs($this->engine->path(), $this->file, $this->engine->fullName(), [
////            'mime_type' => $this->engine->mime(),
////        ]);
//
//
////        return $attachment;
//    }

}
