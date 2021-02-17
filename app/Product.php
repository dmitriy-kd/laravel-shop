<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    //use SoftDeletes; // использование трейтов
    //protected $table = 'product'; //переопределение к какой таблице относится модель
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'new',
        'hit',
        'recommend',
        'count'
    ];
    /*public function getCategory()
    {
        return Category::find($this->category_id);
    }*/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceForCount()
    {
        if (!is_null($this->pivot)) {
            return $this->pivot->count * $this->price;
        }
        return $this->price;
    }

    public function scopeHit($query) // scope - позволяет расширить запрос, делается для выноса абстракции
    {
        return $query->where('hit', 1);
    }

    public function scopeNew($query)
    {
        return $query->where('new', 1);
    }

    public function scopeRecommend($query)
    {
        return $query->where('recommend', 1);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    //Мутатор, изменение значение передаваемого аттрибута в момент сохранения(очень важен шаблон названия функции)
    public function setNewAttribute($value)
    {
        $this->attributes['new'] = $value === 'on' ? 1 : 0;
    }

    public function setHitAttribute($value)
    {
        $this->attributes['hit'] = $value === 'on' ? 1 : 0;
    }

    public function setRecommendAttribute($value)
    {
        $this->attributes['recommend'] = $value === 'on' ? 1 : 0;
    }

    public function isAvailable()
    {
        // $this->trashed(); проверяет является ли товар удаленным по soft delete
        return $this->count > 0;
    }

    public function isNew()
    {
        return $this->new === 1;
    }

    public function isHit()
    {
        return $this->hit === 1;
    }

    public function isRecommend()
    {
        return $this->recommend === 1;
    }
}
