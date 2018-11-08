<?php
Route::group(['middleware' => 'auth', 'prefix' => 'workflow', 'as' => 'workflow.'], function () {
    Route::post('apply', 'ApplyController')->name("apply");
    Route::resource('attributes', 'AttributesController');
    Route::resource('transitions', 'TransitionsController');

    Route::group(['namespace' => 'Api', 'prefix' => 'api', 'as' => 'api.'], function () {
        Route::resource('transitions', 'TransitionsController');
        Route::resource('histories', 'HistoryController');
    });
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('attributes',                               'AttributesController');
        Route::resource('notifications',                            'NotificationsController');
        Route::resource('workflows',                                'WorkflowsController');
        Route::resource('workflows.versions',                       'VersionsController');
        Route::resource('workflows.versions.processes',             'ProcessesController');
        Route::resource('workflows.versions.processes.places',      'PlacesController');
        Route::resource('workflows.versions.processes.transitions', 'TransitionsController');
        Route::resource('workflows.versions.processes.subjects',    'SubjectsController');
        Route::resource('workflows.places',                         'PlacesController');
        Route::resource('workflows.transitions',                    'TransitionsController');
        Route::resource('workflows.subjects',                       'SubjectsController');
    });
});
