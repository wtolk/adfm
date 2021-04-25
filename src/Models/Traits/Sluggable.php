<?php
namespace App\Models\Adfm\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    protected $slugSettings = [
        'source' => 'title',
        'destination' => 'slug'
    ];

    public function setSlugAttribute($value)
    {
        if (strlen($value) == 0) {
            $slug = $this->slugInput($this->{$this->getSource()});
        } else {
            $slug = $this->slugInput($value);
        }
        $slug = substr($slug, 0, 200);
        $slug = $this->getLatestSlug($slug);
        $value = $slug;
        $this->attributes['slug'] = $value;
    }

    /**
     * Получает последнюю версию исходного синонима
     *
     * @param $slug - исходный синоним
     * @return string - строка с преобразованной версией синонима
     */
    public function getLatestSlug($slug)
    {
        while ($this->checkIfSlugExist($slug)) {
            $slug = $this->getNextSlug($this->checkIfSlugExist($slug), $slug);
        }
        return $slug;
    }

    public function slugInput($input)
    {

        return Str::slug($input, '-', 'ru');;
    }

    /**
     * @param $record - Запись синонима из базы
     * @param $slug - исходный синоним
     * @return string - следующий синоним
     */
    public function getNextSlug($record, $slug) {
        $searchString = "/^%s(%s[0-9]+)?$/"; // prepare regexp string to find exact match form return results

        $search = sprintf($searchString, $slug, '-');

        $matches = preg_grep($search, $record);
        foreach ($matches as $match) {
            $pieces = explode('-', $match);
            $endvalues[] = intval(end($pieces));
        }
        $lastDigit = max($endvalues);
        $lastDigit = $lastDigit+1;
        if (count($pieces) > 1) {
            array_pop($pieces);
        }
        $new_slug = implode('-', $pieces) . '-'. $lastDigit ;
        return $new_slug;
    }

    /**
     * Проверяет существует ли такой синоним в базе.
     *
     * @param $slug - синоним
     * @return bool - Возвращает false или существующий синоним
     */
    public function checkIfSlugExist($slug) {
        $item = $this->where($this->getDestination(), '=', $slug)->where('id', '<>', $this->id)
            ->pluck($this->getDestination())
            ->toArray();

        if (empty($item)) {
            return false;
        }
        return $item;
    }


    public function getSource()
    {
        return $this->slugSettings['source'];
    }

    public function getDestination()
    {
        return $this->slugSettings['destination'];
    }
}
