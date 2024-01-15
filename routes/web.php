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

Auth::routes();

Route::get('/', 'WarnsController@index');
Route::get('/home', 'WarnsController@index');
Route::get('/getdata', 'WarnsController@index');
Route::get('/test', 'WarnsController@test');
Route::POST('/regis', 'DataController@regis');

Route::GET('/getdata/empl', 'DataController@empl');
Route::GET('/getdata/empl2', 'DataController@empl2');
Route::GET('/getdata/blacklist_group', 'DataController@blacklist_group');
Route::GET('/getdata/emplid/{emplid}', 'DataController@emplid');
Route::get('/getdata/warning', 'DataController@warning');
Route::get('/getdata/corgroup', 'DataController@corgroup');
Route::get('/getdata/allcorgroup', 'DataController@allcorgroup');
Route::get('/getdata/cor/{corgroup_id}', 'DataController@cor');
Route::get('/getdata/penalty', 'DataController@penalty');
Route::get('/getdata/penaltyqty/{emplid}/{corgroup_id}', 'DataController@penaltyqty');
Route::get('/getdata/loginuser', 'DataController@loginuser');
Route::get('/getdata/corid', 'DataController@corid');
Route::POST('/getdata/corid', 'DataController@corid');
Route::get('/getdata/penaltyid', 'DataController@penaltyid');
Route::get('/getdata/reason', 'DataController@reason');
Route::get('/getdata/edit/{warning_no}', 'DataController@getedit');
Route::get('/getdata/email/{warning_no}', 'DataController@email');
Route::get('/getdata/division', 'DataController@division');
Route::get('/getdata/Cancelcase', 'WarnsController@Cancelcase');
// Route::get('/getdata/email_hr/{warning_no}', 'DataController@email_hr');

//EMAIL
Route::post('/email/lostpass/', 'EmailsController@lostpass');
Route::post('/email/sendmail/', 'EmailsController@sendmail');
Route::GET('/email/detail/{encrypt}', 'EmailsController@detail');
Route::GET('/email/complete/{encrypt}', 'EmailsController@complete');
// Route::GET('/email/approved/{warning_no}/{email}/{level}/{type}', 'EmailsController@approved');
Route::GET('/email/approved/', 'EmailsController@approved');
Route::GET('/email/decline/', 'EmailsController@decline');
// Route::GET('/email/decline/{warning_no}/{email}/{level}/{type}', 'EmailsController@decline');



//Warning 
// Route::resource('warns', 'WarnsController');
Route::get('/warns/create/', 'WarnsController@create');
Route::get('/warns/report/{warning_no}', 'WarnsController@report');
Route::get('/warns/report2/{warning_no}', 'WarnsController@report2');

Route::post('/warns/insert/', 'WarnsController@insert');
Route::POST('/warns/edit/', 'WarnsController@edit');
Route::get('/warns/email/{warning_no}', 'WarnsController@email');

//Admin
Route::group(['middleware' => 'is.admin'], function () {
    Route::get('/admin/test/', 'AdminsController@test');
    Route::get('/admin/corgroup/', 'AdminsController@corgroup');
    Route::get('/admin/regulation/', 'AdminsController@regu');
    Route::get('/admin/email/', 'AdminsController@email');
    Route::get('/admin/user/', 'AdminsController@user');
    Route::get('/admin/blacklist/', 'AdminsController@blacklist');
    Route::get('/admin/blacklistgroup/', 'AdminsController@blacklistgroup');
    Route::get('/admin/penalty/', 'AdminsController@penalty');
});



Route::get('/admin/getcorgroup/', 'AdminsController@getcorgroup');
Route::get('/admin/getregu/', 'AdminsController@getregu');
Route::get('/admin/getemail/', 'AdminsController@getemail');
Route::get('/admin/getuser/', 'AdminsController@getuser');
Route::get('/admin/getblacklist/', 'AdminsController@getblacklist');
Route::get('/admin/getblacklistgroup/', 'AdminsController@getblacklistgroup');
Route::get('/admin/getpenalty/', 'AdminsController@getpenalty');

Route::get('/admin/addcorgroup/', 'AdminsController@addcorgroup');
Route::get('/admin/addregu/', 'AdminsController@addregu');
Route::POST('/admin/addemail/', 'AdminsController@addemail');
Route::POST('/admin/adduser/', 'AdminsController@adduser');
Route::POST('/admin/addblacklist/', 'AdminsController@addblacklist');
Route::POST('/admin/addblacklistgroup/', 'AdminsController@addblacklistgroup');
Route::get('/admin/addpenalty/', 'AdminsController@addpenalty');

Route::get('/admin/editcorgroup/', 'AdminsController@editcorgroup');
Route::get('/admin/editregu/', 'AdminsController@editregu');
Route::POST('/admin/editemail/', 'AdminsController@editemail');
Route::POST('/admin/edituser/', 'AdminsController@edituser');
Route::POST('/admin/editblacklist/', 'AdminsController@editblacklist');
Route::POST('/admin/editblacklistgroup/', 'AdminsController@editblacklistgroup');
Route::get('/admin/editpenalty/', 'AdminsController@editpenalty');

Route::get('/admin/deletecorgroup/', 'AdminsController@deletecorgroup');
Route::get('/admin/deleteregu/', 'AdminsController@deleteregu');
Route::POST('/admin/deleteemail/', 'AdminsController@deleteemail');
Route::POST('/admin/deleteuser/', 'AdminsController@deleteuser');
Route::POST('/admin/deleteblacklist/', 'AdminsController@deleteblacklist');
Route::POST('/admin/deleteblacklistgroup/', 'AdminsController@deleteblacklistgroup');

Route::GET('/admin/activecorgroup/', 'AdminsController@activecorgroup');
Route::GET('/admin/activeregu/', 'AdminsController@activeregu');
Route::GET('/admin/activeemail/', 'AdminsController@activeemail');
Route::GET('/admin/activeusers/', 'AdminsController@activeusers');
Route::GET('/admin/activeblacklist_group/', 'AdminsController@activeblacklist_group');
Route::GET('/admin/activepenalty/', 'AdminsController@activepenalty');
