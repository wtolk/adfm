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
        foreach(config('adfm.fileObserveableModels') as $key => $observeable_model){
            if($file->fileable_type == $observeable_model){
                foreach(config('image-cache.'.$key) as $crop_config){
                    \ImageCache::get($file, $crop_config);
                }
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
