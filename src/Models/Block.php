<?php

namespace App\Models\Adfm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Adfm\Traits\AttachmentTrait;
use App\Models\Adfm\Traits\Sluggable;

class Block extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use AttachmentTrait;

    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('model_relation', '=', 'files')->orderBy('sort');
    }
}
