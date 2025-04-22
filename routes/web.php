<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\InstallerController;
#installer
Route::group(['middleware' => 'web'], function () {

    Route::get('/installer', [InstallerController::class, 'index']);
	Route::get('emailConfiguration', [InstallerController::class, 'emailConfiguration']);
	Route::get('databaseConfiguration', [InstallerController::class, 'databaseConfiguration']);
	Route::get('installPreview', [InstallerController::class, 'installPreview']);
	Route::post('processApplication',[InstallerController::class,'processApplication'])->name("installer.application.process");
	Route::post('/loadForm',[InstallerController::class,'loadForm']);
    Route::post('/installer/checkDbCrud', [InstallerController::class, 'checkDbCrud']);
	Route::post('/installer/checkEmailConfig',[InstallerController::class,'checkEmailConfig']);
	Route::post('/installer/CreatingDB', [InstallerController::class, 'CreatingDB']);
	Route::post('/installer/migrate', [InstallerController::class, 'Migration']);
	Route::post('/installer/seed', [InstallerController::class, 'Seed']);
	Route::post('/installer/createUser', [InstallerController::class, 'createUser']);
	Route::post('/installer/roleBack', [InstallerController::class, 'roleBack']);
	Route::get('/installer/done', [InstallerController::class, 'done']);
});
Route::get('/', function () {
	if (isAppInstalled()) {
       return view('welcome');
    }
	else
		return view('installer.index');
});
