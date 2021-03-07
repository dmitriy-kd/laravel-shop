<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['rate'];

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function IsMain()
    {
        return $this->is_main === 1;
    }
}
