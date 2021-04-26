<?php
namespace App\Models\Adfm\Traits;

use App\Helpers\Dev;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Adfm\File;
use Symfony\Component\HttpFoundation\File\File as RawFile;
use Wtolk\Eloquent\Filter;

trait AttachmentTrait
{
    use Notifiable;
    use Filter;
    public $filesNeedUploadedAfterSaving = [];
    public $filesNeedDeletedAfterSaving = [];

    public function fill($attributes)
    {
        if ($attributes) {
            foreach ($attributes as $key => $attribute) {
//                if ($key == 'categories') {
//
////                    dd($this->isRelation($key, $attribute));
//
//                    \App\Adfm\Helpers\Dev::dd($this->$key(), $attribute);
//                }

                if ($this->isRelation($key, $attribute)) {
                    $this->updateRelationsField($key, $attribute);
                }

                if ($this->isAttributeCropperField($key, $attribute)) {
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

                if ($this->isAttributeMultiFileField($key, $attribute)) {
//                    dd($key, $attribute);
                    $positions = json_decode($attribute['uploader']['positions'], true);
                    $attribute['uploader']['remove'] = json_decode($attribute['uploader']['remove'], true);
                    // Если есть загруженные файлы, добавить в очередь на загрузку
                    if (isset($attribute['files'])) {
                        foreach($attribute['files'] as $i => $file) {
                            foreach ($positions as $position) {
                                if ($position['filename'] == $file->getClientOriginalName()) {
                                    $file->sort = $position['sort'];
                                    $attribute['files'][$i] = $file;
                                }
                            }
                            $this->filesNeedUploadedAfterSaving[] = ['model_relation' => $key, 'file' => $file];
                        }
                    }

                    // Если есть файлы на удаление, то удаляем их
                    if (isset($attribute['uploader']['remove']) && count($attribute['uploader']['remove']) > 0) {
                        File::destroy($attribute['uploader']['remove']);
                    }

                    // Обновляем позиции у ранее загруженных файлов
                    foreach ($positions as $element) {
                        if (isset($element['id'])) {
                            $file = File::find($element['id']);
                            $file->update(['sort' => $element['sort']]);
                        }
                    }


//                    $this->filesNeedUploadedAfterSaving[] = ['model_relation' => $key, 'file' => $attribute];
//                        $uploader = $attribute['uploader'];
//                    dd($key, $attribute, 'tut', $uploader);

                }

                if ($this->isAttributeUploadedFile($key, $attribute)) {
                    // Отдельное свойство, из которого загружаются файлы после сохранения модели
                    if(is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\MorphOne')) {
                        $this->$key()->delete();
                    }

                    $this->filesNeedUploadedAfterSaving[] = ['model_relation' => $key, 'file' => $attribute];
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
            foreach ($model->filesNeedUploadedAfterSaving as $uploadedFile) {
                $f = File::make($uploadedFile['file']);
                $f->fileable_type = get_class($model);
                $f->fileable_id = $model->id;
                $f->model_relation = $uploadedFile['model_relation'];
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

    protected function isAttributeMultiFileField($key, $attribute)
    {
        if (is_array($attribute)) {
            if (array_keys($attribute)[0] === 'uploader') {
                return true;
            }
        }
        return false;
    }

    protected function isAttributeCropperField($key, $attribute)
    {
        if (is_array($attribute)) {
            if (array_keys($attribute)[0] === 'cropper_base64') {
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

    /**
     * универсальный метод для обновления отношений разных типов
     *
     * @param $key - поле
     * @param $attribute - значение поля
     */
    protected function updateRelationsField($key, $attribute)
    {
        // для обновления MenuItem
        if (is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\BelongsTo')) {
            $this->{$this->$key()->getForeignKeyName()} = $attribute;
        }

        // для обновления многие ко многим
        if (is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
            $value = is_array($attribute) ? $attribute : [$attribute];
            $this->$key()->sync($value);
        }
    }

    public function checkFiles($attribute)
    {
        $uploader_data = $attribute['uploader'];
        $positions = json_decode($uploader_data['positions'], true);
        $remove_list = json_decode($uploader_data['remove'], true);
        dd($positions);

        // Удаляем файлы из базы
//        if (count($remove_list) > 0){
//            File::destroy($remove_list);
//        }

        // Приводим массив к правильному виду
        $files_arr = [];
        foreach ($attribute['files'] as $key => $fields) {
            dd($fields[0]);
            foreach ($fields as $i => $field) {
                if (!isset($files_arr[$i])) $files_arr[$i] = [];
                $files_arr[$i][$key] = $field;
            }
        }

        //Загружаем полученные файлы
        $files = [];
        if (!empty($files_arr)) {
            foreach ($files_arr as $file) {
                if ($file['name']) {
                    $files[] = FileController::upload($file, $this->id, get_class($this));
                }
            }
        }

        $user_files_id = [];
        // Обновляем позиции файлов
        foreach ($positions as $key => $position) {
            if (isset($position['id'])) {
                $user_files_id[] = $position['id'];
                File::find($position['id'])->update(['position' => $key + 1]);
            } else {
                foreach ($files as $file){
                    if ($file->name == $position['filename']){
                        $file->update(['position' => $key + 1]);
                        break;
                    }
                }
            }
        }
        return $this;
    }
}
