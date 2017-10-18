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

Route::get('/lang/{lang}', function ($lang) {

    if (array_key_exists($lang, config()->get('app.locales'))) {
        session()->put('locale', $lang);
    }

    return redirect()->back();
})->name('lang');

Auth::routes();


Route::get('/', 'HomeController@welcome')->name('index');
//Route::get('/', 'HomeController@index')->name('index');
Route::get('/help', 'HomeController@help')->name('help');
Route::get('/_test', 'HomeController@__test')->name('_test');

Route::get('/home', 'HomeController@home')->name('home');
Route::post('/java/eds/restapi', 'HomeController@postJavaEdsRestapi')->name('java.eds.restapi');

Route::get('/ticket', 'HomeController@ticket')->name('ticket');

Route::get('/admin', 'AdminController@index')->name('admin.index');



// ОЦЕНКА ПЕРСОНАЛА 360
Route::resource('/competence',"CompetenceController");
Route::resource('/evaluation',"EvaluationController");
Route::resource('/evaluater',"EvaluaterController");
Route::resource('/evalprocess',"EvalProcessController");
Route::resource('/level',"LevelController");



Route::resource('/position',"PositionController");
Route::resource('/func',"FuncController");
Route::resource('/org',"OrgController");
Route::resource('/user',"UserController");



Route::get('/upload', function() {
    return view('upload._image-dialog');
})->name('upload.form.image');

Route::post('/upload', 'HomeController@imageUpload')->name('upload.save.image');
Route::get('/uploaded/{filename}', 'HomeController@getImage')->name('uploaded.image');
Route::get('/uploadeds', 'HomeController@getImages')->name('images.list');

