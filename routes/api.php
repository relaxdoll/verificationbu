<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('inventory', 'API\InventoryController');
Route::resource('tracking', 'API\TrackingController');
Route::resource('info', 'API\InfoController');
Route::resource('image_track', 'API\ImageTrackController');
Route::resource('movement', 'API\MovementController');
Route::resource('image_track_report', 'API\ImageTrackReportController');
Route::resource('image_track_report_job', 'API\ImageTrackJobController');
Route::resource('location', 'API\LocationController');
Route::resource('refuel', 'API\RefuelController');
Route::resource('fleet', 'API\FleetController');
Route::resource('certificate', 'API\CertificateController');
Route::resource('refuelJob', 'API\RefuelJobController');
//Route::resource('line', 'API\LineController');
Route::resource('replaceTire', 'API\ReplaceTireController');
Route::resource('tire', 'API\TireController');
Route::resource('tire_placement', 'API\TirePlacementController');
Route::resource('tire_change_request', 'API\TireChangeRequestController');
Route::resource('tire_pressure_and_tread', 'API\TirePressureAndTreadController');
Route::resource('vehicle', 'API\VehicleController');
Route::resource('driver', 'API\DriverController');
Route::resource('dashboard', 'API\DashboardController');
Route::resource('reason', 'API\ReasonController');
Route::resource('technician', 'API\TechnicianController');
Route::resource('approver', 'API\ApproverController');
Route::resource('place', 'API\PlaceController');
Route::resource('inventory_type', 'API\InventoryTypeController');
Route::resource('maintenance_approval', 'API\MaintenanceApprovalController');
Route::resource('maintenance_detail', 'API\MaintenanceDetailController');
Route::resource('maintenance_inventory_detail', 'API\MaintenanceInventoryDetailController');
Route::resource('vehicle_inspection_list', 'API\VehicleInspectionListController');
Route::resource('vehicle_inspection_log', 'API\VehicleInspectionLogController');
Route::resource('trip_rate_type', 'API\TripRateTypeController');
Route::resource('route', 'API\RouteController');
Route::resource('route_pivot', 'API\RoutePivotController');
Route::resource('trip_rate', 'API\TripRateController');
Route::resource('incentive', 'API\IncentiveController');
Route::resource('job', 'API\JobController');
Route::resource('whtax', 'API\WHTaxController');

Route::name('tracking.')->prefix('tracking')->group(function () {
    $controller = 'API\TrackingController';
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::post('search', $controller . '@search')->name('search');
    });
});

Route::name('movement.')->prefix('movement')->group(function () {
    $controller = 'API\MovementController';
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('all', $controller . '@all')->name('all');
    });
});

Route::name('info.')->prefix('info')->group(function () {
    $controller = 'API\InfoController';
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('activate/{id}', $controller . '@activate')->name('activate');
        Route::get('get_current', $controller . '@getCurrent')->name('get_current');
    });
});

Route::name('job.')->prefix('job')->group(function () {
    $controller = 'API\JobController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
    });
});

Route::name('trip_rate.')->prefix('trip_rate')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\TripRateController';
        Route::get('getTripRateById/{id}', $controller . '@getTripRateById')->name('getTripRateById');
    });
});

Route::name('whtax.')->prefix('whtax')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\WHTaxController';
        Route::get('getBill/{bill_id}', $controller . '@getBill')->name('getBill');
        Route::get('listBill', $controller . '@listBill')->name('listBill');
        Route::post('postWHTax', $controller . '@postWHTax')->name('postWHTax');
        Route::get('getWHTax', $controller . '@getWHTax')->name('getWHTax');
    });
});

Route::name('zoho.')->prefix('zoho')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\ZohoController';
        Route::get('getCode', $controller . '@getCode')->name('getCode');
        Route::get('oauth2callback', $controller . '@oauth2callback')->name('oauth2callback');
        Route::get('getToken', $controller . '@getToken')->name('getToken');
    });
});

Route::name('vendor.')->prefix('vendor')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\VendorController';
        Route::get('getZohoVendor', $controller . '@getZohoVendor')->name('getZohoVendor');
    });
});

Route::name('place.')->prefix('place')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\PlaceController';
        Route::get('getOnelinkGeofence', $controller . '@getOnelinkGeofence')->name('getOnelinkGeofence');
    });
});

Route::name('vehicle_inspection_list.')->prefix('vehicle_inspection_list')->group(function () {
    $controller = 'API\VehicleInspectionListController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('createSameInspectionList', $controller . '@createSameInspectionList')->name('createSameInspectionList');
        Route::get('getInspectionListByVehicleType/{id}', $controller . '@getInspectionListByVehicleType')->name('getInspectionListByVehicleType');
    });
});

Route::post('inventory/validate', 'API\InventoryController@validateAPI');

Route::name('tirePressureAndTread.')->prefix('tirePressureAndTread')->group(function () {
    $controller = 'API\TirePressureAndTreadController';
    Route::get('tireUpdateThisWeek/{vehicle_id}', $controller . '@tireUpdateThisWeek')->name('tireUpdateThisWeek');
    Route::get('timesDriverDoTirePressureJob', $controller . '@timesDriverDoTirePressureJob')->name('timesDriverDoTirePressureJob');
});

Route::name('maintenance_approval.')->prefix('maintenance_approval')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\MaintenanceApprovalController';
        Route::get('getRequestByStatusId/{id}', $controller . '@getRequestByStatusId')->name('getRequestByStatusId');
        Route::get('getRequestByDriver/{id}', $controller . '@getRequestByDriver')->name('getRequestByDriver');
        Route::get('getUnapprovedJobByFleet/{id}', $controller . '@getUnapprovedJobByFleet')->name('getUnapprovedJobByFleet');
        Route::get('isVehicleFinished/{id}', $controller . '@isVehicleFinished')->name('isVehicleFinished');
        Route::get('getDetailByApprovalId/{id}', $controller . '@getDetailByApprovalId')->name('getDetailByApprovalId');
    });
});

Route::name('tire_placement.')->prefix('tire_placement')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\TirePlacementController';
        Route::post('swap', $controller . '@swap')->name('swap');
        Route::post('replace', $controller . '@replace')->name('replace');
        Route::get('test', $controller . '@test')->name('test');
    });
});

Route::name('vehicle.')->prefix('vehicle')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\VehicleController';
        Route::get('list', $controller . '@list')->name('list');
        Route::get('test', $controller . '@test')->name('test');
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('active_tires/{id}', $controller . '@getActiveTires')->name('getActiveTires');
    });
});

Route::name('inventory_types.')->prefix('inventory_types')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\InventoryTypeController';
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('hasSerial/{id}', $controller . '@hasSerial')->name('hasSerial');
    });
});

Route::name('maintenance.')->prefix('maintenance')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\MaintenanceController';
        Route::get('indexByVehicleId/{id}', $controller . '@indexByVehicleId')->name('indexByVehicleId');
    });
});

Route::name('tire.')->prefix('tire')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\TireController';
        Route::get('indexAvailable', $controller . '@indexAvailable')->name('indexAvailable');
        Route::get('indexAvailableOption', $controller . '@indexAvailableOption')->name('indexAvailableOption');
        Route::post('store', $controller . '@liffStore')->name('store');
        Route::get('usedReport', $controller . '@usedReport')->name('usedReport');
        Route::get('indexByReason', $controller . '@indexByReason')->name('indexByReason');
        Route::get('findWhereTireRegister/{serial}', $controller . '@findWhereTireRegister')->name('findWhereTireRegister');
    });
});

Route::name('tire_placement.')->prefix('tire_placement')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\TirePlacementController';
        Route::get('activeTire', $controller . '@getActiveTireByTread')->name('activeTire');
    });
});

Route::name('purchase.')->prefix('purchase')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\PurchaseController';
        Route::get('purchaseByDate', $controller . '@getPurchaseByDate')->name('getPurchaseByDate');
        Route::get('tiresByPurchaseId/{id}', $controller . '@getTiresByPurchaseId')->name('getTiresByPurchaseId');
        Route::get('totalUsedTiresByPurchaseId/{purchase_id}', $controller . '@getTotalUsedTiresByPurchaseId')->name('getTotalUsedTiresByPurchaseId');
    });
});

Route::name('brand.')->prefix('brand')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\BrandController';
        Route::get('group', $controller . '@indexGroup')->name('group');
    });
});

Route::name('setting.')->prefix('setting')->group(function () {
    Route::name('vehicle.')->prefix('vehicle')->group(function () {
        Route::resource('type', 'API\VehicleTypeController');
    });
    Route::name('payment.')->prefix('payment')->group(function () {
        Route::resource('type', 'API\PaymentTypeController');
    });
});

Route::name('refuel.')->prefix('refuel')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\RefuelController';
        Route::post('query', $controller . '@query')->name('query');
        Route::post('error', $controller . '@error')->name('error');
        Route::post('hide', $controller . '@hide')->name('hide');
        Route::post('check', $controller . '@check')->name('check');
        Route::get('test', $controller . '@test')->name('test');
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('zoning', $controller . '@findFuelConsumptionRate')->name('zoning');
        Route::get('view/{id}', $controller . '@view')->name('view');
    });
});

Route::name('refuelJob.')->prefix('refuelJob')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\RefuelJobController';
        Route::get('sent/{id}', $controller . '@sent')->name('sent');
    });
});

Route::name('image_track_report_job')->prefix('image_track_report_job')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\ImageTrackJobController';
        Route::get('sent/{id}', $controller . '@sent')->name('sent');
    });
});

Route::name('inventory.')->prefix('inventory')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\InventoryController';
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('getInventoryByTypeId/{id}', $controller . '@getInventoryByTypeId')->name('getInventoryByTypeId');
    });
});

Route::name('driver.')->prefix('driver')->group(function () {
    $controller = 'API\DriverController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('refreshProfileAvatar', $controller . '@refreshProfileAvatar')->name('refreshProfileAvatar');
        Route::patch('updateAvatar/{id}', $controller . '@updateAvatar')->name('updateAvatar');
        Route::get('getAssignedHead/{id}', $controller . '@getAssignedHead')->name('getAssignedHead');
        Route::get('getAssignedVehicle/{id}', $controller . '@getAssignedVehicle')->name('getAssignedVehicle');
        Route::get('getAssignedTail/{id}', $controller . '@getAssignedTail')->name('getAssignedTail');
        Route::get('getDriverIdByLineId/{id}', $controller . '@getDriverIdByLineId')->name('getDriverIdByLineId');
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('getLiffType/{id}', $controller . '@getLiffType')->name('getLiffType');
        Route::get('getDriverNotUpdateTireThisWeek', $controller . '@getDriverNotUpdateTireThisWeek')->name('getDriverNotUpdateTireThisWeek');

    });
});

Route::name('route.')->prefix('route')->group(function () {
    $controller = 'API\RouteController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('getRouteByCustomerId/{id}', $controller . '@getRouteByCustomerId')->name('getRouteByCustomerId');
    });
});

Route::name('approver.')->prefix('approver')->group(function () {
    $controller = 'API\ApproverController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
    });
});

Route::name('technician.')->prefix('technician')->group(function () {
    $controller = 'API\TechnicianController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::post('changeTire', $controller . '@changeTire')->name('changeTire');
        Route::get('getTechnicianIdByLineId/{id}', $controller . '@getTechnicianIdByLineId')->name('getTechnicianIdByLineId');
        Route::get('getRequestedByTirePlacementId/{id}', $controller . '@getRequestedByTirePlacementId')->name('getRequestedByTirePlacementId');
    });
});

Route::name('customer.')->prefix('customer')->group(function () {
    $controller = 'API\CustomerController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('getCustomerByFleetId/{id}', $controller . '@getCustomerByFleetId')->name('getCustomerByFleetId');
        Route::get('getCustomerReport/{id}', $controller . '@getCustomerReport')->name('getCustomerReport');
        Route::get('test', $controller . '@test')->name('test');

    });
});

Route::name('image_track.')->prefix('image_track')->group(function () {
    $controller = 'API\ImageTrackController';
    Route::get('{id}/edit', $controller . '@edit')->name('edit');
    Route::name('crud.')->prefix('crud')->group(function () use ($controller) {
        Route::get('group', $controller . '@indexGroup')->name('group');
        Route::get('nhp', $controller . '@nhp')->name('nhp');

    });
});

Route::name('image_track_report.')->prefix('image_track_report')->group(function () {
    Route::name('crud.')->prefix('crud')->group(function () {
        $controller = 'API\ImageTrackReportController';
        Route::get('nhp', $controller . '@nhp')->name('nhp');
        Route::get('test', $controller . '@test')->name('test');
        Route::get('view/{id}', $controller . '@view')->name('view');

    });
});

Route::name('user.')->prefix('user')->group(function () {
    $controller = 'API\UserController';
    Route::post('post/miniNav', $controller . '@miniNav')->name('miniNav');
});

Route::name('fastTrack.')->prefix('fastTrack')->group(function () {
    Route::name('get.')->prefix('get')->group(function () {
        $controller = 'API\ImageTrackController';
        Route::get('info/{lineId}', $controller . '@info')->name('info');
        Route::get('report/{customer_id}', $controller . '@report')->name('report');
        Route::get('reportDetail/{report_id}', $controller . '@reportDetail')->name('reportDetail');
        Route::get('flex/{report_id}', $controller . '@getFlex')->name('flex');
    });
});

Route::name('fast_track.')->prefix('fast_track')->group(function () {
    Route::resource('background', 'API\FastTrackBackgroundController');
    Route::name('background.')->prefix('background')->group(function () {
        Route::name('crud.')->prefix('crud')->group(function () {
            $controller = 'API\FastTrackBackgroundController';
            Route::get('activateBackground/{id}', $controller . '@activateBackground')->name('activateBackground');
        });
    });
});

Route::name('line.')->prefix('line')->group(function () {
    Route::resource('user', 'API\LineUserController');
    Route::name('user.')->prefix('user')->group(function () {
        Route::name('crud.')->prefix('crud')->group(function () {
            $controller = 'API\LineUserController';
            Route::get('getRoleDetailByLineId/{id}', $controller . '@getRoleDetailByLineId')->name('getRoleDetailByLineId');
        });
    });

    Route::resource('group', 'API\LineGroupController');
    Route::resource('richmenu', 'API\RichMenuController');
    Route::name('richmenu.')->prefix('richmenu')->group(function () {
        Route::name('crud.')->prefix('crud')->group(function () {
            $controller = 'API\RichMenuController';
            Route::get('test', $controller . '@test')->name('test');
            Route::post('link', $controller . '@link')->name('link');
            Route::post('uploadRichmenu', $controller . '@uploadRichmenu')->name('uploadRichmenu');
        });
    });
    Route::name('group.')->prefix('group')->group(function () {
        $controller = 'API\LineGroupController';
        Route::name('post.')->prefix('post')->group(function () use ($controller) {
            Route::post('initScrapGroup', $controller . '@initScrapGroup')->name('initScrapGroup');
            Route::post('editGroupName', $controller . '@editGroupName')->name('editGroupName');
            Route::post('shareMessage', $controller . '@shareMessage')->name('shareMessage');
        });
        Route::name('get.')->prefix('get')->group(function () use ($controller) {
            Route::get('group/{id}', $controller . '@getGroupName')->name('getGroupName');
            Route::get('getGroupId/{code}', $controller . '@getGroupId')->name('getGroupId');
            Route::get('closeScrapper/{id}', $controller . '@closeScrapper')->name('closeScrapper');
        });
    });
});

Route::resource('vendor', 'API\VendorController');
Route::resource('brand', 'API\BrandController');
Route::resource('tireType', 'API\TireTypeController');
Route::resource('purchase', 'API\PurchaseController');
Route::post('purchase/validate', 'API\PurchaseController@validateAPI');
Route::post('onelink/realtime', 'API\OnelinkController@realtime');

Route::get('location/get/sendOne', 'API\LocationController@sendOne')->name('location.sendOne');

Route::get('user', function (Request $request) {
    return 'here';
});

Route::middleware('auth:api')->group(function () {
});
