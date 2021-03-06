<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
