<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
                        AuthController,
                        DepartmentController,
                        RoleController,
                        PermissionController,
                        UserController,
                        NovelController,
                        FileController,
                        ConfigurationController,
                        ReportController,
                        CompanyController,
                        CityController,
                        CollectionController,
                        CreditLineController,
                        PqrController,
                        CategoryController,
                        ShopController,
                        PointController,
                        StatementController,
                    };

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health', function (Request $request) {
    return 'Health...';
});

Route::get('/download-image-from-url', [FileController::class, 'downloadImageFromUrl'])->name('file.downloadImageFromUrl');

Route::group(["prefix" => "/auth"], function () {
    Route::get('/get-active-token', [AuthController::class, 'getActiveToken'])->name('auth.getActiveToken');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/create', [NovelController::class, 'create'])->name('new.create');
    Route::middleware(['middleware' => 'auth:api'])->post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/company', [CompanyController::class, 'list'])->name('company.list');
Route::get('/department', [DepartmentController::class, 'list'])->name('department.list');
Route::get('/city', [CityController::class, 'list'])->name('city.list');

Route::group(['middleware' => 'auth:api' , "prefix" => "/session"], function () {
    Route::get('/status', function (Request $request) {
        return 'OK';
    });
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/role"], function () {
    Route::get('/list', [RoleController::class, 'list'])->middleware('can:role.list')->name('role.list');
    Route::post('/create', [RoleController::class, 'create'])->middleware('can:role.create')->name('role.create');
    Route::put('/update/{id}', [RoleController::class, 'update'])->middleware('can:role.update')->name('role.update');
    Route::delete('/delete/{id}', [RoleController::class, 'delete'])->middleware('can:role.delete')->name('role.delete');
    Route::get('/get/{id}', [RoleController::class, 'get'])->middleware('can:role.get')->name('role.get');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/permission"], function () {
    Route::get('/list', [PermissionController::class, 'list'])->name('permission.list');
});

Route::get('/user/get/{id}', [UserController::class, 'get'])->name('user.get');
Route::group(['middleware' => 'auth:api' , "prefix" => "/user"], function () {
    Route::get('/list/{displayAll}', [UserController::class, 'list'])->name('user.list');
    Route::get('/list-by-role-name/{displayAll}/{name}/{city}', [UserController::class, 'listByRoleName'])->name('user.listByRoleName');
    Route::get('/list-by-area/{area}', [UserController::class, 'listByArea'])->name('user.listByArea');
    Route::post('/create', [UserController::class, 'create'])->middleware('can:user.create')->name('user.create');
    Route::put('/update/{id}', [UserController::class, 'update'])->middleware('can:user.update')->name('user.update');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('can:user.delete')->name('user.delete');
    Route::put('/update-profile/{id}', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::put('/update-push-token', [UserController::class, 'updatePushToken'])->name('user.updatePushToken');
    Route::put('/update-location', [UserController::class, 'updateLocation'])->name('user.updateLocation');
    Route::put('/complete-data/{id}', [UserController::class, 'completeData'])->name('user.completeData');
});

Route::get('/new/get-news-migrate', [NovelController::class, 'getNewsMigrate'])->name('user.getNewsMigrate');
Route::group(['middleware' => 'auth:api' , "prefix" => "/new"], function () {
    Route::get('/list/{status}', [NovelController::class, 'list'])->name('new.list');
    Route::put('/update/{id}', [NovelController::class, 'update'])->name('new.update');
    Route::put('/complete-data/{id}', [NovelController::class, 'completeData'])->name('review.completeData');
    Route::delete('/delete/{id}', [NovelController::class, 'delete'])->middleware('can:new.delete')->name('new.delete');
    Route::get('/get/{id}', [NovelController::class, 'get'])->name('new.get');
    Route::get('/get-by-phone/{phone}', [NovelController::class, 'getByPhone'])->name('new.getByPhone');
    Route::post('/complete-data-saac', [NovelController::class, 'completeDataSaac'])->name('new.completeDataSaac');
});

Route::get('/download-file-from-url', [FileController::class, 'downloadFileFromUrl'])->name('file.downloadFileFromUrl');
Route::group(['middleware' => 'auth:api' , "prefix" => "/file"], function () {
    Route::post('/create', [FileController::class, 'create'])->name('file.create');
    Route::delete('/delete/{id}', [FileController::class, 'delete'])->name('file.delete');
    Route::post('/get', [FileController::class, 'get'])->name('file.get');
    Route::get('/list-statuses-today', [FileController::class, 'listStatusesToday'])->name('file.listStatusesToday');
    Route::put('/update/{id}', [FileController::class, 'update'])->name('file.update');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/configuration"], function () {
    Route::get('/', [ConfigurationController::class, 'index'])->name('parameter.list');
    Route::get('/{id}', [ConfigurationController::class, 'show'])->middleware('can:parameter.list')->name('parameter.get');
    Route::post('/', [ConfigurationController::class, 'store'])->middleware('can:parameter.list')->name('parameter.create');
    Route::put('/{id}', [ConfigurationController::class, 'update'])->middleware('can:parameter.list')->name('parameter.update');
    Route::delete('/{id}', [ConfigurationController::class, 'destroy'])->middleware('can:parameter.list')->name('parameter.delete');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/report"], function () {
    Route::get('/', [ReportController::class, 'list'])->name('report.list');
    Route::get('/{id}', [ReportController::class, 'execute'])->name('report.execute');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/collection"], function () {
    Route::get('/{document}', [CollectionController::class, 'list'])->name('collection.list');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/credit-line"], function () {
    Route::get('/', [CreditLineController::class, 'list'])->name('creditLine.list');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/category"], function () {
    Route::get('/', [CategoryController::class, 'list'])->name('category.list');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/shop"], function () {
    Route::get('/', [ShopController::class, 'list'])->name('shop.list');
    Route::get('/by-status/{status}', [ShopController::class, 'listByStatus'])->name('shop.listByStatus');
    Route::post('/', [ShopController::class, 'create'])->name('shop.create');
    Route::put('/{id}', [ShopController::class, 'update'])->name('shop.update');
    Route::delete('/{id}', [ShopController::class, 'delete'])->name('shop.delete');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/pqr"], function () {
    Route::post('/', [PqrController::class, 'create'])->name('pqr.create');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/statement"], function () {
    Route::get('/{id}', [StatementController::class, 'get'])->name('statement.get');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/point"], function () {
    Route::get('/{status}', [PointController::class, 'list'])->name('point.list');
    Route::get('/by-user-session/{status}', [PointController::class, 'listByUserSession'])->name('point.listByUserSession');
    Route::post('/', [PointController::class, 'create'])->name('point.create');
    Route::put('/{id}', [PointController::class, 'update'])->name('point.update');
    Route::delete('/{id}', [PointController::class, 'delete'])->name('point.delete');
    Route::get('/{id}', [PointController::class, 'get'])->name('point.get');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/credit"], function () {
    Route::get('/{status}', [CreditController::class, 'list'])->name('credit.list');
    Route::post('/', [CreditController::class, 'create'])->name('credit.create');
    Route::put('/{id}', [CreditController::class, 'update'])->name('credit.update');
    Route::delete('/{id}', [CreditController::class, 'delete'])->name('credit.delete');
    Route::get('/{id}', [CreditController::class, 'get'])->name('credit.get');
});
