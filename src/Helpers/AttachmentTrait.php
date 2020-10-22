<?php
namespace Wtolk\Adfm\Helpers;

use App\Helpers\Dev;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Whoops\Exception\ErrorException;
use Wtolk\Adfm\Events\ModelWasFilled;
use Wtolk\Adfm\Models\File;

trait AttachmentTrait
{
    use Notifiable;

    public $filesNeedUploadedAfterSaving = [];

    public function fill($attributes)
    {
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
                if ($this->isAttributeUploadedFile($key, $attribute)) {
                    // Отдельное свойство, из которого загружаются файлы после сохранения модели
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
}
