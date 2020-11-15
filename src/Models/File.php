<?php

namespace App\Adfm\Models;

use App\Helpers\Dev;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mimey\MimeTypes;

/**
 * Wtolk\Adfm\Models\File
 *
 * @property int $id
 * @property string $name
 * @property string $original_name
 * @property string $mime
 * @property string|null $extension
 * @property int $size
 * @property int $sort
 * @property string $path
 * @property string|null $description
 * @property string|null $alt
 * @property string|null $hash
 * @property string $disk
 * @property string|null $model_name
 * @property int|null $model_id
 * @property string|null $model_relation
 * @property int|null $user_id
 * @property string|null $group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereModelRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUserId($value)
 * @mixin \Eloquent
 */
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
        'sort',
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

    public function getUrl()
    {
        return Storage::disk($this->disk)->url($this->name.'.'.$this->extension);
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
            'sort'          => isset($this->file->sort) ? $this->file->sort : 0,
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
//            dd($this->file, $this->file->getClientOriginalName());
            $this->storage->putFileAs('files', $this->file, $this->name().'.'.$this->extension());
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
