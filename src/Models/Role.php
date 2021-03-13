<?php
namespace App\Models\Adfm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Adfm\Helpers\AttachmentTrait;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;
    use AttachmentTrait;

    protected $fillable = [
        'name',
        'guard_name'
    ];
}
