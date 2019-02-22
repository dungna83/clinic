<?php

    Route::get('/', 'DoctorController@index')->name('home.index');

    Route::resource('doctors', 'DoctorController', ['parameters' => ['users' => 'id'], 'except' => ['show']]);
