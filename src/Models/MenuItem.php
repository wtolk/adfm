<?php
namespace App\Models\Adfm;

use App\Helpers\Dev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Adfm\Traits\AttachmentTrait;
use Illuminate\Support\Facades\URL;

/**
 * Wtolk\Adfm\Models\MenuItem
 *
 * @property int $id
 * @property string $title
 * @property int $menu_id
 * @property int $parent_id
 * @property int $is_published
 * @property int $position
 * @property string|null $link
 * @property string|null $model_name
 * @property int|null $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|MenuItem[] $children_recursive
 * @property-read int|null $children_recursive_count
 * @property-read \Wtolk\Adfm\Models\File|null $image
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|MenuItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|MenuItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MenuItem withoutTrashed()
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AttachmentTrait;

    protected $fillable = [
        'title',
        'is_published',
        'link',
        'parent_id',
        'menu_id',
        'model_name',
        'model_id',
        'position',
        'select'
    ];

    public static function syncHierarchy($json_string)
    {
        $hierarchy = json_decode($json_string, true);
        $data = [];
        DB::transaction(function () use ($hierarchy) {
            foreach ($hierarchy as $position => $element) {
                $parent_id = $element['parent_id'] ?? 0;
                MenuItem::find($element['id'])->fill(['parent_id' => $parent_id, 'position' => $position])->save();
            }
        });

        return $data;
    }

    public function children_recursive()
    {
        return $this->hasMany('App\Models\Adfm\MenuItem', 'parent_id');
    }

    public function children()
    {
        return $this->children_recursive()->with('children');
    }

    public function image()
    {
        return $this->morphOne(File::class, 'fileable')->where('model_relation', '=', 'image');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getLinkAttribute($value)
    {
        if (substr($value, 0, 4) == 'http') {
            return $value;
        } else {
            $url = new URL();
            $r = $url->getFacadeRoot()->getRequest();
            if (substr($value, 0, 1) != '/' ) { $value = '/'.$value; }
            return $r->getScheme().'://'.$r->getHost().$value;
        }
    }
}
