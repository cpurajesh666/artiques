<?php

Route::group(['namespace' => 'Botble\Promotions\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'promotions', 'as' => 'promotions.'], function () {

            Route::resource('', 'PromotionController')->parameters(['' => 'promotion']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PromotionController@deletes',
                'permission' => 'promotions.destroy',
            ]);
        });

    });

});
