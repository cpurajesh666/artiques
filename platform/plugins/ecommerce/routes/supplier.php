<?php

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers\Customers', 'middleware' => ['web', 'core']],
    function () {
        Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
            Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
                Route::resource('', 'SupplierController')->parameters(['' => 'supplier']);

                Route::delete('items/destroy', [
                    'as'         => 'deletes',
                    'uses'       => 'CustomerController@deletes',
                    'permission' => 'customers.destroy',
                ]);
            });

            Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
                Route::get('get-list-suppliers-for-select', [
                    'as'         => 'get-list-suppliers-for-select',
                    'uses'       => 'CustomerController@getListSupplierForSelect',
                    'permission' => 'customers.index',
                ]);

                Route::get('get-list-suppliers-for-search', [
                    'as'         => 'get-list-suppliers-for-search',
                    'uses'       => 'CustomerController@getListSupplierForSearch',
                    'permission' => ['customers.index', 'orders.index'],
                ]);

                // Route::post('update-email/{id}', [
                //     'as'         => 'update-email',
                //     'uses'       => 'CustomerController@postUpdateEmail',
                //     'permission' => 'customers.edit',
                // ]);

                // Route::get('get-customer-addresses/{id}', [
                //     'as'         => 'get-customer-addresses',
                //     'uses'       => 'CustomerController@getCustomerAddresses',
                //     'permission' => ['customers.index', 'orders.index'],
                // ]);

                // Route::get('get-customer-order-numbers/{id}', [
                //     'as'         => 'get-customer-order-numbers',
                //     'uses'       => 'CustomerController@getCustomerOrderNumbers',
                //     'permission' => ['customers.index', 'orders.index'],
                // ]);

                // Route::post('create-customer-when-creating-order', [
                //     'as'         => 'create-customer-when-creating-order',
                //     'uses'       => 'CustomerController@postCreateCustomerWhenCreatingOrder',
                //     'permission' => ['customers.index', 'orders.index'],
                // ]);
            });
        });
    });
