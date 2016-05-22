<?php

Auth::loginUsingId(2);

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'list'], function (\Illuminate\Routing\Router $r) {
    Route::get('/subcategory/{cat_id?}', 'ListController@subcategory');
    Route::get('/item/{subcat_id?}', 'ListController@item');
    Route::get('/category', 'ListController@category');
    Route::get('/location', 'ListController@location');
    Route::get('/business-unit', 'ListController@businessUnit');
    Route::get('/priority', 'ListController@priority');
    Route::get('/urgency', 'ListController@urgency');
    Route::get('/impact', 'ListController@impact');
    Route::get('/support-groups', 'ListController@supportGroup');
    Route::get('/technician', 'ListController@technician');
});

Route::group(['prefix' => 'admin'], function (\Illuminate\Routing\Router $r) {
    $r->resource('region', 'Admin\RegionController');
    $r->resource('city', 'Admin\CityController');
    $r->resource('location', 'Admin\LocationController');
    $r->resource('business-unit', 'Admin\BusinessUnitController');
    $r->resource('branch', 'Admin\BranchController');
    $r->resource('department', 'Admin\DepartmentController');
    $r->resource('category', 'Admin\CategoryController');
    $r->resource('subcategory', 'Admin\SubcategoryController');
    $r->resource('item', 'Admin\ItemController');
    $r->resource('status', 'Admin\StatusController');
    $r->resource('group', 'Admin\GroupController');
    $r->resource('priority', 'Admin\PriorityController');
    $r->resource('urgency', 'Admin\UrgencyController');
    $r->resource('impact', 'Admin\ImpactController');
    $r->resource('business-rule', 'Admin\BusinessRuleController');
    $r->resource('sla', 'Admin\SlaController');
});

Route::resource('ticket', 'TicketController');
Route::group(['prefix' => 'ticket'], function (\Illuminate\Routing\Router $r) {
    $r->post('resolution/{ticket}', ['as' => 'ticket.resolution', 'uses' => 'TicketController@resolution']);
    $r->post('reply/{ticket}', ['as' => 'ticket.reply', 'uses' => 'TicketController@reply']);
    $r->post('jump', ['as' => 'ticket.jump', 'uses' => 'TicketController@jump']);
    $r->post('approval/{ticket}', ['as' => 'ticket.approval', 'uses' => 'TicketController@approval']);
    $r->get('approve/{ticket}', ['as' => 'ticket.show-approval', 'uses' => 'TicketController@showApproval']);
    $r->post('approve/{ticket}', ['as' => 'ticket.approve', 'uses' => 'TicketController@approve']);
    $r->post('resend-approval/{ticket}', ['as' => 'ticket.resend-approval', 'uses' => 'TicketController@resendApproval']);
    $r->delete('remove-approval/{ticket}', ['as' => 'ticket.remove-approval', 'uses' => 'TicketController@resendApproval']);
});
