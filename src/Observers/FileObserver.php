<?php

namespace App\Observers\Adfm;

use App\Models\Adfm\File;

class FileObserver
{
    /**
     * Handle the File "created" event.
     *
     * @param  \App\Models\Adfm\File  $file
     * @return void
     */
    public function created(File $file)
    {
        if($file->fileable_type == 'App\Models\Adfm\Catalog\Product'){
            foreach(config('image-cache.product') as $crop_config){
                \ImageCache::get($file, $crop_config);
            }
        }
    }

    /**
     * Handle the File "updated" event.
     *
     * @param  \App\Models\Adfm\File  $file
     * @return void
     */
    public function updated(File $file)
    {
        //
    }

    /**
     * Handle the File "deleted" event.
     *
     * @param  \App\Models\Adfm\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        //
    }

    /**
     * Handle the File "restored" event.
     *
     * @param  \App\Models\Adfm\File  $file
     * @return void
     */
    public function restored(File $file)
    {
        //
    }

    /**
     * Handle the File "force deleted" event.
     *
     * @param  \App\Models\Adfm\File  $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        //
    }
}
