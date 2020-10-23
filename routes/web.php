<?php

use App\AccessCode;
use App\Book;
use Carbon\Carbon;
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


Route::get('/relationship', function() {


    $used_access_code = AccessCode::where('code', '1100-7881-1451-2906')->first();
    if($used_access_code) {
        return $used_access_code->license_name;
    } else {
        return "Not Found";
    }
    // $access_code = AccessCode::withTrashed()->with('books')->where('uuid', '1843baa5e2a5b140744851733dabe0b1')->get();
    // return $access_code;
    // return $access_code->books;

    // $book_access = BookAccessCode::where('access_code_uuid', '779d819e656cb24ab0fb284f5f732b50')->delete();
    // return $book_access;

    // $exclude_books = explode(',', $access_code->books_contained);

    // $filtered_books = Book::whereNotIn('id', $exclude_books)->get();

    // return $filtered_books;
    // $book = Book::find(20);

    // $access_code->books()->detach();
    // return 'Success';
    // foreach($access_code->books as $book) {
    //     echo $book->title . "<br>";
    // }

    // $book = Book::find(1);
    // return $book->access_codes;

    // foreach($book->codes as $code) {
    //     echo $code->pivot;
    // }
});

Route::get('/', function(){
    // $date = Carbon::createFromDate(2020, 9, 4);
    // $date = Carbon::createFromTimeString('2020-09-04 12');
    // if(Carbon::now() > $date) {
    //     return "todate id";
    // }
    // return $date->format('Y-m-d h:i:s A');
    // // return $now->yesterday();
    return redirect('admin\dashboard');
});
// Auth::routes();
Route::group(['prefix' => 'admin',  'namespace' => 'Admin'], function () {

    // Authentication Controller Goes Here
    Route::group(['namespace' => 'Auth'], function () {
        Route::get('/register', 'SpectrumAdminAuthController@create');
        Route::post('/register', 'SpectrumAdminAuthController@store')->name('admin/register');

        Route::get('/login', 'SpectrumAdminAuthController@show_login_page')->name('admin/login');
        Route::post('/login', 'SpectrumAdminAuthController@login');


        Route::post('/logout', 'SpectrumAdminAuthController@logout')->name('admin.logout');
    });

    // Dashboard Conroller goes Here
    Route::get('/dashboard', 'SpectrumAdminDashboardController@index')->name('admin/dashbard');

    Route::get('/audit-logs', 'SpectrumAdminDashboardController@audit_logs');

    // Notification Controller Goes Here
    Route::resource('/notification', 'AdminNotificationController');

    // License Controller Goes Here
    Route::group(['prefix' => 'licenses', 'namespace' => 'License'], function() {
        Route::get('/license-groups', 'SpectrumLicenseController@load_license_groups')->name('license.group');
        Route::get('/keys', 'SpectrumLicenseController@index')->name('keys.index');
        Route::get('/create-key', 'SpectrumLicenseController@create')->name('keys.create');
        Route::post('/store-key', 'SpectrumLicenseController@store')->name('keys.store');
        Route::post('/generate_key', 'SpectrumLicenseController@check_if_code_exist')->name('license.generate');
        Route::get('/used-licenses', 'SpectrumLicenseController@used_licenses')->name('license.used');
        Route::get('/findUser', 'SpectrumLicenseController@findUser');
        Route::get('/all-used-licenses', 'SpectrumLicenseController@all_used_licenses')->name('license.all_used');
        Route::post('/load_all_licenses', 'SpectrumLicenseController@load_all_licenses')->name('license.load_all');

        Route::delete('/delete-license/{uuid}', 'SpectrumLicenseController@destroy');
        Route::get('/edit-license', 'SpectrumLicenseController@edit_license');
        Route::delete('/remove-book/{book_id}', 'SpectrumLicenseController@remove_book');

        Route::get('/recycled-keys', 'SpectrumLicenseController@thrashedLicense');
        Route::delete('/force-delete', 'SpectrumLicenseController@delete_by_force');
        Route::put('/restore-license', 'SpectrumLicenseController@restoreDeleted');
        Route::post('/load_thrashed_licenses', 'SpectrumLicenseController@load_thrashed_license')->name('license.thrashed');

        Route::post('/distinct-books', 'SpectrumLicenseController@distinctBook');
        Route::put('/update-license', 'SpectrumLicenseController@update');

        Route::get('/accounting-module', 'SpectrumLicenseController@accounting_module');
    });

    Route::group(['prefix' => 'books', 'namespace' => 'Books'], function() {
        Route::get('/uploaded-books', 'SpectrumBooksController@index');
        Route::get('/create-books', 'SpectrumBooksController@create');

    });


    Route::group(['namespace' => 'Developer'], function() {
        Route::resource('api_accesskey_management', 'DeveloperApikeyController');
        Route::get('view-apiaccess-key', 'DeveloperApikeyController@showKey');
        Route::get('get-apiaccess-keys', 'DeveloperApikeyController@showAll');
    });


    Route::group(['prefix' => 'accounts',  'namespace' => 'Account'], function () {

        Route::get('/activate-accounts', 'SpectrumAccountController@index');
        Route::get('/load-accounts/{status}', 'SpectrumAccountController@create');
        Route::delete('/delete-account/{id}', 'SpectrumAccountController@destroy');
        Route::put('/activate-account/{id}', 'SpectrumAccountController@activate_account');
        Route::put('/alter-priviledge/{id}', 'SpectrumAccountController@switch_privilege');

    });

    Route::group(['prefix' => 'support',  'namespace' => 'UserSupport'], function () {
        Route::get('/contacts', 'UserSupportController@get');
        Route::get('/conversation/{id}', 'UserSupportController@getMessagesFor');
        Route::post('/conversation/send', 'UserSupportController@send');
       
    });

});



