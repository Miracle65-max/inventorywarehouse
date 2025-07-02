<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileManagementController;
use App\Http\Controllers\RecentlyDeletedController;
use App\Http\Controllers\BarcodeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

// Barcode image route should be public
Route::get('/barcode/{itemCode}/generate', [App\Http\Controllers\BarcodeController::class, 'generateBarcode'])->name('barcode.generate');

// Protect all sensitive routes with auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('items', ItemController::class);
    Route::resource('sales-orders', App\Http\Controllers\SalesOrderController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('audit-trails', App\Http\Controllers\AuditTrailController::class)->only(['index']);

    // User Management routes
    Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::get('user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
    Route::post('user-management', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::get('user-management/{user}', [UserManagementController::class, 'show'])->name('user-management.show');
    Route::get('user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
    Route::put('user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::delete('user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
    Route::post('user-management/{user}/suspend', [UserManagementController::class, 'suspend'])->name('user-management.suspend');
    Route::post('user-management/{user}/approve', [UserManagementController::class, 'approve'])->name('user-management.approve');
    Route::post('user-management/{user}/change-password', [UserManagementController::class, 'changePassword'])->name('user-management.change-password');
    Route::post('user-management/{user}/reset-login-attempts', [UserManagementController::class, 'resetLoginAttempts'])->name('user-management.reset-login-attempts');

    // Profile Management routes
    Route::get('profile-management', [ProfileManagementController::class, 'index'])->name('profile-management.index');
    Route::get('profile-management/{user}', [ProfileManagementController::class, 'show'])->name('profile-management.show');
    Route::get('profile-management/{user}/edit', [ProfileManagementController::class, 'edit'])->name('profile-management.edit');
    Route::put('profile-management/{user}', [ProfileManagementController::class, 'update'])->name('profile-management.update');
    Route::post('profile-management/{user}/avatar', [ProfileManagementController::class, 'updateAvatar'])->name('profile-management.avatar');
    Route::delete('profile-management/{user}/avatar', [ProfileManagementController::class, 'deleteAvatar'])->name('profile-management.avatar.delete');
    Route::post('profile-management/{user}/change-password', [ProfileManagementController::class, 'changePassword'])->name('profile-management.change-password');
    Route::get('profile-management/{user}/export', [ProfileManagementController::class, 'exportProfile'])->name('profile-management.export');

    // Stock Movements
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/list', [App\Http\Controllers\ItemController::class, 'listNotifications']);
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\ItemController::class, 'markNotificationRead']);
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\ItemController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::get('/api/notifications/unread-count', function () {
        $user = Auth::user();
        $count = Notification::forUser($user->id)->unread()->count();
        return response()->json(['count' => $count]);
    });

    // User Profile
    Route::get('/user-profile', [UserProfileController::class, 'show'])->name('user-profile.show');
    Route::get('/user-profile/edit', [UserProfileController::class, 'edit'])->name('user-profile.edit');
    Route::put('/user-profile', [UserProfileController::class, 'update'])->name('user-profile.update');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/profile/change-password', [UserProfileController::class, 'showChangePassword'])->name('profile.password');
    Route::put('/profile/change-password', [UserProfileController::class, 'changePassword'])->name('profile.password.update');

    // Warehouse Management
    Route::get('/warehouse-management', [App\Http\Controllers\WarehouseManagementController::class, 'index'])->name('warehouse-management.index');
    Route::post('/warehouse-management/complete-order/{order}', [App\Http\Controllers\WarehouseManagementController::class, 'completeOrder'])->name('warehouse-management.completeOrder');

    // Recently Deleted
    Route::get('/recently-deleted', [RecentlyDeletedController::class, 'index'])->name('recently-deleted.index');
    Route::post('/recently-deleted/restore-all', [RecentlyDeletedController::class, 'restoreAll'])->name('recently-deleted.restore-all');
    Route::post('/recently-deleted/destroy-all', [RecentlyDeletedController::class, 'destroyAll'])->name('recently-deleted.destroy-all');
    Route::post('/recently-deleted/{type}/{id}/restore', [RecentlyDeletedController::class, 'restore'])->name('recently-deleted.restore');
    Route::delete('/recently-deleted/{type}/{id}', [RecentlyDeletedController::class, 'destroy'])->name('recently-deleted.destroy');

    // Sales Orders custom actions
    Route::post('/sales-orders/process/{orderId}', [App\Http\Controllers\SalesOrderController::class, 'processOrder'])->name('sales-orders.processOrder');
    Route::post('/sales-orders/cancel/{orderId}', [App\Http\Controllers\SalesOrderController::class, 'cancelOrder'])->name('sales-orders.cancelOrder');
    Route::delete('/sales-orders/delete/{orderId}', [App\Http\Controllers\SalesOrderController::class, 'deleteOrder'])->name('sales-orders.deleteOrder');
    Route::post('sales-orders/{sales_order}/status', [App\Http\Controllers\SalesOrderController::class, 'updateStatus'])->name('sales-orders.updateStatus');
    Route::get('/inventory-status/ajax-search', [App\Http\Controllers\InventoryStatusController::class, 'ajaxSearch'])->name('inventory-status.ajax-search');
    Route::get('/items/{id}/current-stock', [App\Http\Controllers\ItemController::class, 'currentStock']);
    
    // Barcode routes
    Route::get('/barcode/{itemCode}', [BarcodeController::class, 'showBarcode'])->name('barcode.show');

    Route::get('/test-layout', function() {
        return view('profile.test_layout', ['user' => Auth::user()]);
    });
    Route::get('/role-test', function() {
        return 'Role middleware works!';
    });

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/notification_status.php';
require __DIR__.'/auth.php';
