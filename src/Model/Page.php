<?php


namespace Wtolk\Adfm\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class Page
{
    use SoftDeletes;

//    use MenuLinkable;
//    use FilesTrait;

    protected $fillable = [
        'title',
        'content_raw'
    ];

//    public function image()
//    {
//        return $this->belongsTo('\ADFM\Model\File');
//    }
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
//    /*Преобразовываем json атрибут к обычному массиву */
//    protected $casts = [
//        'settings' => 'array',
//    ];
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
