<?php

Route::group(['namespace' => 'Botble\Report\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {

            Route::get('/inventory', 'ReportController@inventory')->name('inventory');

            Route::get('/products-sold', 'ReportController@productsSold')->name('products.sold');

            Route::get('/guest-users', 'ReportController@guestUsers')->name('guest.users');
            
            Route::get('/most-viewed', 'ReportController@mostViewed')->name('mostViewed');
          
            Route::get('/top-selling', 'ReportController@topSelling')->name('topSelling');
            

            
            
            // Ajax

            Route::get('/most-viewed-category-data', 'ReportController@mostViewedCategoryData')->name('mostViewedCategoryData');
            Route::get('/most-viewed-sub-category-data', 'ReportController@mostViewedSubCategoryData')->name('mostViewedSubCategoryData');
            Route::get('/most-viewed-sub-sub-category-data', 'ReportController@mostViewedSubSubCategoryData')->name('mostViewedSubSubCategoryData');
            Route::get('/most-viewed-product-data', 'ReportController@mostViewedProductData')->name('mostViewedProductData');
            Route::get('/most-searched-data', 'ReportController@mostSearchedData')->name('mostSearchedData');

            Route::get('/top-selling-category-data', 'ReportController@topSellingCategoryData')->name('topSellingCategoryData');
            Route::get('/top-selling-sub-category-data', 'ReportController@topSellingSubCategoryData')->name('topSellingSubCategoryData');
            Route::get('/top-selling-sub-sub-category-data', 'ReportController@topSellingSubSubCategoryData')->name('topSellingSubSubCategoryData');
            Route::get('/top-selling-product-data', 'ReportController@topSellingProductData')->name('topSellingProductData');
            Route::get('/top-selling-supplier-data', 'ReportController@topSellingSupplierData')->name('topSellingSupplierData');

      
            
            //Inventory filter with employee
            Route::post('/report-products-inventory', 'ReportController@inventoryReport')->name('products-inventory');

                        
            //Inventory filter without employee
            Route::post('/report-products-count', 'ReportController@productsSoldReport')->name('products-count');

            Route::post('/product-report', 'ReportController@categoryProductFilter')->name('product-filter');
        });
    });
});
