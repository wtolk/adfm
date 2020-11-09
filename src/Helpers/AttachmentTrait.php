<?php
namespace App\Adfm\Helpers;

use App\Helpers\Dev;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Adfm\Models\File;
use Symfony\Component\HttpFoundation\File\File as RawFile;

trait AttachmentTrait
{
    use Notifiable;

    public $filesNeedUploadedAfterSaving = [];

    public function fill($attributes)
    {
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
                if ($this->isRelation($key, $attribute)) {
                    $this->updateRelationsField($key, $attribute);
                }

                if ($this->isAttributeBase64String($key, $attribute)) {
                    if ($attribute['cropper_base64'] == null) {
                        unset($attributes[$key]);
                    } else {
                        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
                        $content = $attribute['cropper_base64'];
                        $content = Arr::last(explode(',', $content));
                        $content = base64_decode($content);
                        file_put_contents($tmpFilePath, $content);
                        $tmpFile = new RawFile($tmpFilePath);
                        $file = new UploadedFile(
                            $tmpFile->getPathname(),
                            $attribute['original_name'],
                            $tmpFile->getMimeType(),
                            0,
                            true // Mark it as test, since the file isn't from real HTTP POST.
                        );
                        $attribute = $file;
                    }

                }
                if ($this->isAttributeUploadedFile($key, $attribute)) {
                    // Отдельное свойство, из которого загружаются файлы после сохранения модели
                    if(is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\MorphOne')) {
                        $this->$key()->delete();
                    }
                    $this->filesNeedUploadedAfterSaving[$key] = $attribute;
                    unset($attributes[$key]);
                }
            }
        }
        parent::fill($attributes);
        return $this;
    }

    protected static function booted()
    {
        /*
         * Загружает файлы после сохранения модели.
         * Нужно на случай, если фаил загружают в еще не созданную модель
         *
         * */
        static::saved(function ($model) {
            foreach ($model->filesNeedUploadedAfterSaving as $key => $attribute) {
                $f = File::make($attribute);
                $f->model_name = get_class($model);
                $f->model_id = $model->id;
                $f->model_relation = $key;
                $f->upload();
            }
        });
    }

    protected function isAttributeUploadedFile($key, $attribute)
    {
        if (is_object($attribute) && get_class($attribute) == 'Illuminate\Http\UploadedFile') {
            if (method_exists($this, $key) && is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\Relation')) {
                return true;
            }
        }
        return false;
    }

    protected function isAttributeBase64String($key, $attribute)
    {
        if (is_array($attribute)) {
            if (array_keys($attribute)[0] == 'cropper_base64') {
                return true;
            }
        }
        return false;
    }

    protected function isRelation($key, $attribute)
    {
        if (method_exists($this, $key) && is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\Relation')) {
            return true;
        }
    }

    protected function updateRelationsField($key, $attribute)
    {
        if (is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\BelongsTo')) {
            $this->{$this->$key()->getForeignKeyName()} = $attribute;
        }
    }
}
