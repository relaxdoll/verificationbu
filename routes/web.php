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

Route::get('image_tracks/nhp', 'PublicController@index');

Route::name('webhook.')->prefix('/webhook')->group(function () {
    Route::post('payment', 'PaymentController@store')->name('payment');
    Route::post('line', 'LineController@receive')->name('line');
    Route::post('onelink', 'LineController@receive')->name('line');
});

Route::resource('liff', 'LiffController');

Route::get('refuelJob', 'LiffController@refuelJob');
Route::get('getCustomerGroup', 'LiffController@getCustomerGroup');
Route::get('imageTrackJob', 'LiffController@imageTrackJob');



Route::get('/certified', function () {
    return view('certified_product');
});

Route::get('/inspect_status', function () {
    return view('inspect_status');
});

Route::get('/container_tracking/{slug}', 'TrackingPageController@show');
//Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/test', 'LineController@test');

Route::get('/lineLogin', 'AuthController@lineLogin');

Route::group(['middleware' => 'auth'], function () {
    Route::get('table-list', function () {
        return view('pages.table_list');
    })->name('table');

    Route::get('typography', function () {
        return view('pages.typography');
    })->name('typography');

    Route::get('icons', function () {
        return view('pages.icons');
    })->name('icons');

    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');

});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('landing');
    });
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::get('user/get', 'UserController@get');
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);


    Route::resource('certificate', 'CertificateController');
    Route::resource('inventory', 'InventoryController');
    Route::resource('places', 'PlaceController');
    Route::resource('replacetires', 'ReplaceTireController');
    Route::resource('tracking', 'VehicleController');
    Route::resource('info', 'DriverController');
    Route::resource('route', 'RouteController');
    Route::resource('technician', 'TechnicianController');
    Route::resource('approver', 'ApproverController');
    Route::resource('job', 'JobController');
    Route::resource('route', 'RouteController');
    Route::resource('refuel', 'RefuelController');
    Route::resource('image_tracks', 'ImageTrackController');
    Route::resource('purchases', 'PurchaseController');
    Route::resource('inventory_type', 'InventoryTypeController');
    Route::resource('tire', 'TireController');
    Route::resource('brand', 'BrandController');
    Route::resource('vehicle_inspection_list', 'VehicleInspectionListController');

    Route::resource('broker', 'BrokerController');
    Route::resource('vendors', 'VendorController');
    Route::resource('movement', 'CustomerController');
    Route::resource('location', 'LocationController');
    Route::resource('whtax', 'WHTaxController');

    Route::name('zohos.')->prefix('/zohos')->group(function () {
        Route::name('forms.')->prefix('/forms')->group(function () {
            Route::get('advance', 'ZohoFormController@advance');
            Route::get('invoice', 'ZohoFormController@invoice');
        });
    });

    Route::name('lines.')->prefix('/lines')->group(function () {
        Route::resource('group', 'LineGroupController');
        Route::resource('user', 'LineUserController');
        Route::resource('rich_menus', 'RichMenuController');
    });

    Route::name('google_drives.')->prefix('/google_drives')->group(function () {
        $controller = 'GoogleDriveController';
        Route::get('test', $controller . '@test')->name('test');
    });

    Route::name('settings.')->prefix('/settings')->group(function () {
        $controller = 'SettingController';
        Route::get('vehicle', $controller . '@vehicle')->name('vehicle');
    });

    Route::name('maintenance.')->prefix('/maintenance')->group(function () {
        $controller = 'MaintenanceController';
        Route::get('tire', $controller . '@tire')->name('tire');
        Route::get('replaceTire', $controller . '@replaceTire')->name('replaceTire');
        Route::get('expiredTireReport', $controller . '@expiredTireReport')->name('expiredTireReport');
        Route::get('technician', $controller . '@technician')->name('technician');
    });

    Route::name('fasttracks.')->prefix('/fasttracks')->group(function () {
        Route::resource('backgrounds', 'FastTrackBackgroundController');
    });

    Route::name('refuel.')->prefix('/refuel')->group(function () {
        Route::name('crud.')->prefix('crud')->group(function () {
            $controller = 'RefuelController';
            Route::get('report', $controller . '@report')->name('report');
            Route::get('error', $controller . '@error')->name('error');
        });
    });

    Route::get('certificate/{name}/{course}/{givenDate}/{lecturerName}/{lecturerPosition}', function ($name, $course, $givenDate, $lecturerName, $lecturerPosition) {
        return view('certificates.create')->with('name', $name)->with('course', $course)->with('givenDate', $givenDate)->with('lecturerName', $lecturerName)->with('lecturerPosition', $lecturerPosition);
    });
});

Route::name('onelinks.')->prefix('/onelinks')->group(function () {
    $controller = 'OnelinkController';
    Route::get('store', $controller . '@store')->name('store');
});

Route::name('locations.')->prefix('/locations')->group(function () {
    $controller = 'LocationController';
    Route::get('send', $controller . '@send')->name('send');
});
