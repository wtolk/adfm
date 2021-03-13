<?php

namespace App\Models\Adfm;

use App\Helpers\Dev;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mimey\MimeTypes;
use Whoops\Exception\ErrorException;

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
    protected $appends = ['url'];
    /**
     * @var UploadedFile
     */
    protected $file;
    protected $storage;
    protected $time;
    protected $mimes;


    protected $disk;

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
        'fileable_type',
        'fileable_id',
        'model_relation',
        'group',
        'user_id',
    ];

    public $model_relation = null;
    public $fileable_id = null;
    public $fileable_type = null;

    public static function make(UploadedFile $file)
    {
        $item = new self();
        $item->disk = env('DEFAULT_FILE_STORAGE');
        $item->file = $file;
        $item->mimes = new MimeTypes();
        $item->storage = Storage::disk($item->disk);
        return $item;
    }

    public function name(): string
    {
        return sha1(time().$this->file->getClientOriginalName());
    }

    public function getPath(): string
    {
        if ($this->disk == 'yandex-cloud' && empty(env('YANDEX_STORAGE_FOLDER'))) {
            throw new ErrorException('Нужно задать папку для yandex storage вида site.ru в файле .env
                параметр - YANDEX_STORAGE_FOLDER или указать локальное хранилище', 500);
        }
        return isset($this->path) ? $this->path.$this->name.'.'.$this->extension : 'user_files/'.date('Y/m/d', time()).$this->name.'.'.$this->extension;
    }

    public function getDirectory()
    {
        return isset($this->path) ? $this->path : 'user_files/'.date('Y/m/d', time());
    }

    public function getUrl()
    {
        return Storage::disk($this->disk)->url($this->getDirectory().$this->filename);
    }

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getFilenameAttribute()
    {
        return $this->name.'.'.$this->extension;
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
//        \App\Adfm\Helpers\Dev::dd($this->file->getPath());
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

    /**
     * Сохраняет фаил в указанное место без сохранения модели
     *
     * @param $path
     */
    public function putFile($path, $name = null)
    {
        $name = ($name != null) ? $name : $this->name().'.'.$this->extension();
        $this->storage->putFileAs($path, $this->file, $name);
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
            'path'          => Str::finish($this->getDirectory(), '/'),
            'disk'          => $this->disk,
            'fileable_type'    => $this->fileable_type,
            'fileable_id'    => $this->fileable_id,
            'model_relation'    => $this->model_relation,
            'group'         => $this->group,
            'user_id'       => Auth::id(),
        ];
        $this->fill($data);
        $attachment = $this->getMatchesHash();
        if ($attachment === null) {
            $this->putFile($this->getDirectory());
            return $this->save();
        }

        $attachment = $attachment->replicate()->fill([
            'original_name' => $this->file->getClientOriginalName(),
            'sort'          => 0,
            'user_id'       => Auth::id(),
            'group'         => $this->group,
            'fileable_type'    => $this->fileable_type,
            'fileable_id'    => $this->fileable_id,
            'model_relation'    => $this->model_relation
        ]);
        $attachment->file = $this->file;
        $attachment->time = time();
        $attachment->mimes = new MimeTypes();
        $attachment->storage = Storage::disk($this->disk);
        $attachment->save();



        return $attachment;

    }
}
