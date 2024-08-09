<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplateCommentController;
use App\Http\Controllers\UpdateStatusKaryawanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Guest
Route::middleware("guest")->group(function () {
    // Home
    Route::get("/", [HomeController::class, "index"])->name("home");

    // Auth
    Route::get("/login", [AuthController::class, "login"])->name("login");
    Route::post("/login", [AuthController::class, "actionLogin"])->name("action-login");
});

// Authenticated
Route::middleware(["auth"])->group(function () {
    // Auth
    Route::get("/logout", [AuthController::class, "logout"])->name("logout");

    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "dashboard"])->name("dashboard");

    // Dashboard Menu
    Route::prefix("dashboard")->group(function() {
        // Ticket
        Route::get("ticket", [TicketController::class, "index"])->name("tickets");
        Route::post("send-reply", [CommentController::class, "sendReply"])->name("sendReply");
        Route::get("ticket/{id}", [TicketController::class, "show"])->name("ticket-detail");

        // Profile
        Route::get("/profil", [ProfilController::class, "edit"])->name("profil.index");
        Route::patch("/profil/{id}", [ProfilController::class, "update"])->name("profil.update");

        Route::middleware(["role:admin"])->group(function () {
            // Karyawan
            Route::resource("karyawan", KaryawanController::class);
            Route::post('karyawan/ajax/status-active', [UpdateStatusKaryawanController::class, 'updateStatus'])->name('karyawan.updateStatus');
       });

        Route::middleware(["role:admin,customer-service"])->group(function () {
            Route::get("ticket-create", [TicketController::class, "create"])->name("ticket-create");
            Route::post("ticket-create", [TicketController::class, "store"])->name("ticket-store");
        });

        Route::middleware(["role:admin,agent"])->group(function () {
            Route::get("pickup-ticket/{id}", [TicketController::class, "pickupTicket"])->name("pickup-ticket");
            Route::delete("ticket/{id}", [TicketController::class, "destroy"])->name("ticket.delete");
            Route::put("update-status-ticket/{id}", [TicketController::class, "updateTicketStatus"])->name("update-status-ticket");
            Route::get("explore-ticket", [TicketController::class, "exploreIndex"])->name("explore-ticket-index");
            Route::post("update-ticket", [TicketController::class, "updateTicket"])->name("update.ticket");

            // Template Balasan
            Route::get("template-comment", [TemplateCommentController::class, "index"])->name("template-comments");
            Route::post("template-comments", [TemplateCommentController::class, "store"])->name("template-comments.store");
            Route::delete("template-comments/{id}", [TemplateCommentController::class, "destroy"])->name("template-comments.destroy");
        });
    });



    // Route::post("/dashboard/karyawan/ajax/status-active", [KaryawanController::class, "updateStatusActive"]);
    // Route::resource("dashboard/status", StatusKaryawanController::class);

    // Route::resource("karyawan", KaryawanController::class);
    // Route::post("karyawan/ajax/status-active", [KaryawanController::class, "updateStatusActive"]);

    // Route::resource("status", StatusKaryawanController::class);

    // Notification
    // Route::get("notification", [NotificationController::class, "index"])->name("notification.index");
    // Route::get("notification/{id}", [NotificationController::class, "read"])->name("notification.read");
    // Route::get("notification/read/all", [NotificationController::class, "readAll"])->name("notification.readall");
    // Route::get("notification/clear/read", [NotificationController::class, "clearRead"])->name("notification.clearread");
});
