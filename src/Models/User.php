<?php
namespace App\Models\Adfm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $guard_name = 'web';
    protected $appends = ['role'];


    public function setPasswordAttribute($value)
    {
        //Если пароль пустой, то не нужно обновлять информацию о нем
        if (strlen($value) != 0) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function getRoleAttribute()
    {
        return (count($this->roles) == 0) ? 'Без роли' : $this->roles[0]->name;
    }

    public function getFirstRole()
    {
        return $this->roles->first();
    }
}
