<?php
namespace Wtolk\Adfm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Wtolk\Adfm\Helpers\AttachmentTrait;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;
    use AttachmentTrait;

    protected $fillable = [
        'name',
        'guard_name'
    ];
}
