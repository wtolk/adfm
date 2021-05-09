<?php
namespace App\Models\Adfm;

use App\Helpers\Adfm\Dev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Adfm\Traits\Sluggable;

/**
 * Wtolk\Adfm\Models\Menu
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wtolk\Adfm\Models\MenuItem[] $links
 * @property-read int|null $links_count
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Query\Builder|Menu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Menu withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Menu withoutTrashed()
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        'title',
        'slug'
    ];

    public function links()
    {
        return $this->hasMany('App\Models\Adfm\MenuItem', 'menu_id', 'id')->where('parent_id', 0)->with('children')->orderBy('position');
    }

    public static function getData($slug)
    {
        $uri = request()->getPathInfo();
        $menu = Menu::where('slug', $slug)->first();
        $links = MenuItem::where('menu_id', $menu->id)->orderBy('position')->get();
        $tree = [];
        foreach ($links as $link) {
            if ($uri == '/'.$link['link']) {
                $link['status'] = 'active';
            }
            $tree[$link['parent_id']][] = $link;
        }
        return $tree;
    }
}
