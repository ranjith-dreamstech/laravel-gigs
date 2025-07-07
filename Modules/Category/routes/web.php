<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('category', CategoryController::class)->names('category');
});

Route::group(['middleware' => ['checkInstallerStatus', 'setLocale']], function () {
    Route::group(['prefix' => 'admin','middleware' => 'admin'], function () {
        Route::get('category', [CategoryController::class, 'index'])->name('admin.category');
        Route::post('category/save', [CategoryController::class, 'store'])->name('admin.category_store');
        Route::post('category/list', [CategoryController::class, 'list'])->name('admin.category_list');
        Route::post('category/delete', [CategoryController::class, 'delete'])->name('admin.category_delete');
        Route::get('subcategory', [CategoryController::class, 'subCategoryIndex'])->name('admin.subCategoryIndex');
        Route::post('subcategory/save', [CategoryController::class, 'subCategoryStore'])->name('admin.subCategoryStore');
        Route::post('subcategory/list', [CategoryController::class, 'subCategoryList'])->name('admin.subCategoryList');
    });
});
