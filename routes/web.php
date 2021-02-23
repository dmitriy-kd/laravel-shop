<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes([
    'reset' => false,
    'verify' => false,
]);

Route::get('/logout', 'Auth\LoginController@logout')->name('get-logout');

Route::get('/', function () {
	return view('index');
});

Route::middleware(['auth'])->group(function() {

    Route::group([
        'namespace' => 'Person',
        'prefix' => 'person',
        'as' => 'person.',
    ], function() {
        Route::get('/orders', 'OrderController@index')->name('orders.index');
        Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
    });
    Route::group([
        'namespace' => 'Admin',
        'prefix' => 'admin',
    ], function() {
        Route::group(['middleware' => 'is_admin'], function() {
            Route::get('/orders', 'OrderController@index')->name('home');
            Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
        });

        Route::resource('categories', 'CategoryController');
        Route::resource('products', 'ProductController');

    });
});


Route::get('/', 'MainController@index')->name('index');

Route::post('/subscription/{product}', 'MainController@subscribe')->name('subscription');

Route::get('/categories', 'MainController@categories')->name('categories');

Route::get('/card/{card}', 'MainController@card')->name('card');

Route::group(['prefix' => 'basket'], function() {
//    Route::post('/add/{id}', 'BasketController@basketAdd')->name('basket-add'); вариант с передачей не id, а сразу объекта называется Model Injection
    Route::post('/add/{product}', 'BasketController@basketAdd')->name('basket-add');
//    Route::post('/remove/{id}', 'BasketController@basketRemove')->name('basket-remove');
    Route::post('/remove/{product}', 'BasketController@basketRemove')->name('basket-remove');
    Route::group(['middleware' => 'basket_not_empty'], function() {
        Route::get('/', 'BasketController@basket')->name('basket');
        Route::get('/place', 'BasketController@basketPlace')->name('basket-place');
        Route::post('/confirm', 'BasketController@basketConfirm')->name('basket-confirm');
    });
});

//Route::get('/mobiles/{product?}', 'MainController@product')->name('product'); // знак ? - означает что параметр необязателен


Route::get('/{category}', 'MainController@category')->name('category');
Route::get('/{category}/{product?}', 'MainController@product')->name('product');
