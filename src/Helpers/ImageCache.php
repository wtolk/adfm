<?php


namespace App\Adfm\Helpers;


use App\Adfm\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ImageCache
{
    public $preset;
    public $preset_name;
    public $image_manager;
    public $img_file;
    public $img_cache_path;
    public $alt;
    public $title;

    public function __toString()
    {
        return $this->img();
    }
    public static function get($file, $preset) {
        $image_cache = new self();
        $image_cache->preset_name = $preset;
        $image_cache->img_file = $file;
        $presets = config('imagecache.presets');
        if (!isset($presets[$preset])) {
            throw new \Exception('Нет такого стиля для изображения в конфиге imagecache.presets');
        } else {
            $image_cache->preset = $presets[$preset];
        }
        $image_cache->image_manager  = new \Intervention\Image\ImageManagerStatic;
        $image_cache->process();
        return $image_cache;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function alt($alt)
    {
        $this->alt = $alt;
        return $this;
    }

    public function img()
    {
        return $this->generateImageElement();
    }
    public function src()
    {
        return $this->img_cache_path;
    }

    public function cache()
    {
        $this->img_cache_path = Storage::disk($this->img_file->disk)->url($this->getPath().$this->img_file->filename);
        Cache::forever($this->img_file->getPath(), Storage::disk($this->img_file->disk)->url($this->getPath().$this->img_file->filename));
    }

    public function process()
    {
        $path_file = $this->img_file->disk == 'local'
            ? Storage::disk('local')->path($this->img_file->getPath())
            : Storage::disk($this->img_file->disk)->url($this->img_file->getPath());

        if (Cache::has($this->img_file->getPath())){
            $this->img_cache_path = Cache::get($this->img_file->getPath());
            return $this;
        }

        if (Storage::disk($this->img_file->disk)->exists($this->getPath().$this->img_file->filename)) {
            $this->cache();
            return $this;
        }

        $img = $this->image_manager->make($path_file);
        $operation = $this->preset['method'];
        $parametres = $this->preset['parametres'];
        $img->$operation(...$parametres);

        $this->save($img);
        $this->cache();
        return $this;
    }


    protected function generateImageElement() {
        return '<img src="'. $this->img_cache_path .'"  alt="'. $this->alt .'" title="'. $this->title .'"/>';
    }

    private function save($img)
    {
        $img->encode();
        // Создаем временный фаил
        $temp = tmpfile();
        fwrite($temp, $img->getEncoded());
        $t = new UploadedFile(stream_get_meta_data($temp)['uri'], $this->img_file->filename, $img->mime, null, TRUE);
        // Загружаем без сохранения модели
        $file = File::make($t);
        $file->putFile($this->getPath(), $this->img_file->filename);
    }

    /**
     * Путь для сохранения картинки
     *
     * @return string
     */
    private function getPath()
    {
        return 'user_files/imagecache/' .$this->preset_name . '/' . $this->img_file->getDirectory();
    }
}
