<?php


namespace App\Helpers\Adfm;

use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\SymfonyResponseFactory;

class ImageCache
{
    public $title;
    public $alt;
    public $class_name;
    public $id;
    public $url;

    public static function get($file, $params) {
        $imageCache = new self();

        $server = \League\Glide\ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => new \League\Flysystem\Filesystem(
                Storage::disk($file->disk)->getDriver()->getAdapter()
            ),
            'cache' => new \League\Flysystem\Filesystem(
                Storage::disk($file->disk)->getDriver()->getAdapter()
            ),
            'cache_path_prefix' => 'image_cache'
        ]);

        $st = Storage::disk($file->disk);
        $url = $st->url($server->getCachePath($file->getPath(), $params ));
        if (!Cache::has($server->getCachePath($file->getPath(), $params ))) {
            $server->makeImage($file->getPath(), $params );
            Cache::forever($server->getCachePath($file->getPath(), $params), $url);
        }
        $imageCache->url = $url;
        return $imageCache;
    }

    public function __toString() {
        return '<img class="'.$this->class_name.'" src="'. $this->url .'"  alt="'.$this->alt.'" title="'.$this->title.'"/>';
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
    public function className($class)
    {
        $this->class_name = $class;
        return $this;
    }
    public function id($id)
    {
        $this->id = $id;
        return $this;
    }
}
