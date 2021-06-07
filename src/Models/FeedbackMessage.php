<?php
namespace App\Models\Adfm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Adfm\Traits\AttachmentTrait;
use App\Models\Adfm\Traits\Sluggable;

class FeedbackMessage extends Model
{
    use HasFactory;
    use AttachmentTrait;

    protected $table = 'adfm_feedback_messages';

    protected $fillable = [
        'fields'
    ];

    /*Преобразовываем json атрибут к обычному массиву */
    protected $casts = [
        'fields' => 'array',
    ];
}
