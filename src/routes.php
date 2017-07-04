<?php

Route::group(['middleware' => 'web'], function () {
	Route::get(config('adminamazing.path').'/users', 'selfreliance\iusers\UsersController@index')->name('AdminUsers');
	Route::get(config('adminamazing.path').'/users/{id}', 'selfreliance\iusers\UsersController@edit')->name('AdminUsersEdit');
	Route::put(config('adminamazing.path').'/users/{id}', 'selfreliance\iusers\UsersController@update')->name('AdminUsersUpdate');
	Route::delete(config('adminamazing.path').'/users/{id}', 'selfreliance\iusers\UsersController@destroy')->name('AdminUsersDeleted');

	Route::post(config('adminamazing.path').'/users/loginwith/{id}', 'selfreliance\iusers\UsersController@loginwith')->name('AdminUsersLoginWith');
});