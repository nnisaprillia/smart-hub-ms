<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\BorrowingController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CheckInController;
use App\Http\Controllers\Web\EquipmentController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\UserController;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/public-borrowings/{borrowing}', [
    HomeController::class,
    'showBorrowing'
])->name('public.borrowings.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member routes
    Route::get('borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::get('my-borrowings', [BorrowingController::class, 'myBorrowings'])->name('borrowings.mine');

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::resource('rooms', RoomController::class);
        Route::resource('equipment', EquipmentController::class);
        Route::resource('borrowings', BorrowingController::class)->except(['create', 'store', 'show']);
        Route::resource('users', UserController::class);
        Route::resource('check-ins', CheckInController::class);

        // Aksi khusus
        Route::patch('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::patch('borrowings/{borrowing}/cancel',  [BorrowingController::class, 'cancel'])->name('borrowings.cancel');
    });
});

require __DIR__.'/auth.php';
