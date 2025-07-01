<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationStatusController;
use App\Http\Controllers\InventoryStatusController;

Route::middleware(['auth'])->group(function () {
    Route::get('/notification-status', [NotificationStatusController::class, 'index'])->name('notification-status.index');
    
    Route::get('/inventory-status', [InventoryStatusController::class, 'index'])->name('inventory-status.index');
    
    // Temporarily comment out role middleware
    // Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::post('/inventory-status/return-item', [InventoryStatusController::class, 'returnItem'])->name('inventory-status.return-item');
    // });
    
    // Temporarily comment out role middleware
    // Route::middleware(['role:super_admin'])->group(function () {
        Route::post('/inventory-status/repair-item', [InventoryStatusController::class, 'repairItem'])->name('inventory-status.repair-item');
        Route::post('/inventory-status/dispose-item', [InventoryStatusController::class, 'disposeItem'])->name('inventory-status.dispose-item');
    // });
});