<?php


namespace App\Traits;


use Illuminate\Support\Facades\App;
use LogicException;


trait Translatable
{
    protected $defaultLocale = 'ru';

    public function __($originalFieldName)
    {
        $locale = App::getLocale() ?? $this->defaultLocale;
        if ($locale === 'en') {
            $fieldName = $originalFieldName . '_en';
        } else {
            $fieldName = $originalFieldName;
        }
//dd($fieldName, $this->attributes);
        if (in_array($fieldName, array_keys($this->attributes), true)) {
            if ($locale === 'en' && (is_null($this->$fieldName)) || empty($this->$fieldName)) {
                return $this->$originalFieldName;
            }
            return $this->$fieldName;
        } else {
            throw new LogicException('no such attribute ' . get_class($this));
        }
    }

}
