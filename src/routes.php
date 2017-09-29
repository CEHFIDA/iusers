<?php

Route::group(['prefix' => 'admin/users', 'middleware' => 'web'], function() {
	Route::get('/', 'selfreliance\iusers\UsersController@index')->name('AdminUsers');
	Route::get('/{id}', 'selfreliance\iusers\UsersController@edit')->name('AdminUsersEdit');
	Route::put('/{id}', 'selfreliance\iusers\UsersController@update')->name('AdminUsersUpdate');
	Route::delete('/{id}', 'selfreliance\iusers\UsersController@destroy')->name('AdminUsersDeleted');
	Route::post('/loginwith/{id}', 'selfreliance\iusers\UsersController@loginwith')->name('AdminUsersLoginWith');
    Route::post('/searchUsers', 'selfreliance\iusers\UsersController@searchUsers')->name('AdminSearchUsers');
});