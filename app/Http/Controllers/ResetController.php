<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetController extends Controller
{
    public function reset()
    {
        //выполнение артизан команды через класс, сама команда позволяет выполнить сиды, флаг fresh позволяет
        //выполнить заново все сиды даже если они ранее уже вызывались
        Artisan::call('migrate:fresh --seed');
        //позволяет удалить директорию
        Storage::deleteDirectory('categories');
        //создание директории
        Storage::makeDirectory('categories');
        //заполнение "хранилище" файлами с "диска"
        $files = Storage::disk('reset')->files('categories');
        foreach ($files as $file) {
            Storage::put($file, Storage::disk('reset')->get($file));
        }
        //теперь динамический вариант для копирования файлов из всех нужных категорий
        foreach (['categories', 'products'] as $folder) {
            Storage::deleteDirectory($folder);
            Storage::makeDirectory($folder);
            $files = Storage::disk('reset')->files($folder);
            foreach ($files as $file) {
                Storage::put($file, Storage::disk('reset')->get($file));
            }
        }

    }

}
