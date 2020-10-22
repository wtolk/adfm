<?php


namespace Wtolk\Adfm\Models;


use App\Helpers\Dev;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Wtolk\Adfm\Helpers\AttachmentTrait;
use Wtolk\Adfm\Helpers\Sluggable;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;
//    use MenuLinkable;
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
        return $this->morphOne(File::class, 'model_relation', 'model_name', 'model_id')
            ->where('model_relation', '=', 'image');
    }
//
//    public function files()
//    {
//        return $this->morphMany('\ADFM\Model\File', 'entity');
//    }
//
//    public function link()
//    {
//        return $this->hasOne('\ADFM\Model\Link', 'link', 'alias');
//    }
//
//    public function tax_categories()
//    {
//        return $this->belongsToMany(Tax_Category::class, 'pages_tax_category', 'page_id', 'tax_category_id', 'id');
//    }
//
//    public function tax_post_tags()
//    {
//        return $this->belongsToMany(Tax_Post_Tag::class, 'pages_tax_post_tag', 'page_id', 'tax_post_tag_id', 'id');
//    }
//
//    public function setSettingsAttribute($value)
//    {
//        if (isset($value['developer-mode'] ) && $value['developer-mode'] != '0') {
//            $value['developer-mode'] = 'on';
//        } else {
//            $value['developer-mode'] = 'off';
//        }
//        $this->attributes['settings'] = json_encode($value);
//    }
//

//
//    /**
//     * @return string ссылка для меню
//     */
//    public function getLinkPath() {
//        return $this->alias;
//    }
//
//    /**
//     * @return string Название ссылки для меню
//     */
//    public function getLinkTitle() {
//        return $this->title;
//    }
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
