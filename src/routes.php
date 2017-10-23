<?php

Route::group(['prefix' => config('adminamazing.path').'/users', 'middleware' => ['web','CheckAccess']], function() {
	Route::get('/', 'selfreliance\iusers\UsersController@index')->name('AdminUsers');
	Route::get('/{id}', 'selfreliance\iusers\UsersController@edit')->name('AdminUsersEdit');
	Route::put('/{id}', 'selfreliance\iusers\UsersController@update')->name('AdminUsersUpdate');
	Route::delete('/{id?}', 'selfreliance\iusers\UsersController@destroy')->name('AdminUsersDelete');
	Route::post('/loginwith/{id}', 'selfreliance\iusers\UsersController@loginwith')->name('AdminUsersLoginWith');
});