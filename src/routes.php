<?php

Route::group(['middleware' => 'web'], function () {
	Route::get('admin/users', 'selfreliance\iusers\UsersController@index')->name('AdminUsers');
	Route::get('admin/users/{id}', 'selfreliance\iusers\UsersController@edit')->name('AdminUsersEdit');
	Route::put('admin/users/{id}', 'selfreliance\iusers\UsersController@update')->name('AdminUsersUpdate');
	Route::delete('admin/users/{id}', 'selfreliance\iusers\UsersController@destroy')->name('AdminUsersDeleted');
});

