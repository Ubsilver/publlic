<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit"
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );
});

Route::middleware("auth")->group(function () {
    Route::get("/", [ReportController::class, "index"])->name("reports");
    Route::post("/reports", [ReportController::class, "store"])->name(
        "reports.store"
    );
    Route::get("/reports/{report}", [ReportController::class, "show"])->name(
        "report.show"
    );
    Route::put("/reports/{report}", [ReportController::class, "update"])->name(
        "report.update"
    );
    Route::delete("/reports/{report}", [ReportController::class, "destroy"])->name(
        "reports.destroy"
    );
    Route::put("/reports/{report}/status", [ReportController::class, "updateStatus"])->name(
        "report.update-status"
    );
});

Route::middleware("auth")->group(function () {
    Route::post("/user/make-admin", [ProfileController::class, "makeAdmin"])->name("user.make-admin");
    Route::post("/user/make-user", [ProfileController::class, "makeUser",])->name("user.make-user");
});

require __DIR__ . "/auth.php";
