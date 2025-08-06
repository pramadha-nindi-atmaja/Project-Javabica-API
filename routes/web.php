<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Gate;
use Opcodes\LogViewer\Facades\LogViewer;

use \Rakutentech\LaravelRequestDocs\Controllers\LaravelRequestDocsController;

/*
============================================================================
routes untuk documenntation APIs
============================================================================
*/
Route::group([ 'prefix' => '/system-app'], function () {

        Route::get('logout', [AuthController::class, 'logoutDocs']);
        Route::get('/login', function () { return view('login-docs'); })->name('login');
        Route::post('login-docs', [AuthController::class, 'loginDocs']);
        Route::get('/payment', function () { return view('payment'); });
        Route::group([ 'middleware' => 'auth:apps'], function () {
              
                Route::get('/home', function () { return view('system-apps-menu'); });
                /*
                ============================================================================
                routes di by pass dari library => rakutentech/laravel-request-docs
                ============================================================================
                */
                Route::get('api-documentation', [LaravelRequestDocsController::class, 'index'])->name('request-docs.index');
                 /*
                ============================================================================
                routes dan view di by pass dari library => opcodesio/log-viewer
                ============================================================================
                */
                Route::get('/log-monitor', function () {
                        LogViewer::auth();
                
                        $selectedFile = LogViewer::getFile(request()->query('file', ''));            
                        return view('log-view.index', ['selectedFileIdentifier' => $selectedFile?->identifier]);})->name('blv.index');
                
                        Route::get('file/{fileIdentifier}/download', function (string $fileIdentifier) {
                        $file = LogViewer::getFile($fileIdentifier);
                
                        abort_if(is_null($file), 404);
                
                        Gate::authorize('downloadLogFile', $file);
                
                        return $file->download();
                })->name('blv.download-file');
        });
});



