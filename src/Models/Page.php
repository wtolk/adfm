<?php


namespace App\Models\Adfm;


use App\Helpers\Adfm\Dev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Adfm\Traits\AttachmentTrait;
use App\Models\Adfm\ILinkMenu;
use App\Models\Adfm\Traits\MenuLinkable;
use App\Models\Adfm\Traits\Sluggable;

/**
 * Wtolk\Adfm\Models\Page
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property array|null $options
 * @property array|null $meta
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Wtolk\Adfm\Models\File|null $image
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Query\Builder|Page onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Page withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Page withoutTrashed()
 * @mixin \Eloquent
 */
class Page extends Model implements ILinkMenu
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
    use MenuLinkable;
    use AttachmentTrait;

    /*Преобразовываем json атрибут к обычному массиву */
    protected $casts = [
        'meta' => 'array',
        'options' => 'array',
    ];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta',
        'options'
    ];

    public function image()
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('model_relation', '=', 'image');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('model_relation', '=', 'files')->orderBy('sort');
    }


    /**
     * @return string ссылка для меню
     */
    public function getLinkPath() {
        return $this->slug;
    }

    /**
     * @return string Название ссылки для меню
     */
    public function getLinkTitle() {
        return $this->title;
    }
//
//    public function breadcrumbs()
//    {
//        return [
//            ['uri' => '/'.$this->alias, 'title' => $this->title],
//        ];
//    }
//
//
//    /**
//     * Генерирует метатайтл
//     *
//     * @return string
//     */
//    public function getMetaTitle() {
//        if (strlen($this->meta_title) > 0) {
//            return $this->meta_title;
//        } else {
//            return $this->title;
//        }
//
//    }
//
//    /**
//     * Генерирует метаdescription
//     *
//     * @return string
//     */
//    public function getMetaDescription() {
//        if (strlen($this->meta_description) > 0) {
//            return $this->meta_description;
//        } else {
//            return mb_substr(strip_tags( str_replace('&nbsp;', ' ', $this->content)), 0, 140);
//        }
//
//    }
}
