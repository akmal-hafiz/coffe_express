<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\LoyaltyController as AdminLoyaltyController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

// Public routes
Route::get("/", function () {
    $promos = \App\Models\Promo::where("active", true)
        ->latest()
        ->take(3)
        ->get();
    $news = \App\Models\News::latest()->take(4)->get();
    return view("index", compact("promos", "news"));
})->name("home");

Route::get("/menu", function () {
    $coffeeMenus = \App\Models\Menu::active()->coffee()->get();
    $nonCoffeeMenus = \App\Models\Menu::active()->nonCoffee()->get();
    return view("menu", compact("coffeeMenus", "nonCoffeeMenus"));
})->name("menu");

Route::get("/contact", function () {
    return view("contact");
})->name("contact");

Route::post("/contact", [
    App\Http\Controllers\ContactController::class,
    "store",
])->name("contact.store");

// Public reviews page
Route::get("/reviews", [ReviewController::class, "index"])->name(
    "reviews.index",
);

// Language switcher
Route::get("/lang/{locale}", function ($locale) {
    if (in_array($locale, ["en", "id"])) {
        session(["locale" => $locale]);
    }
    return redirect()->back();
})->name("lang.switch");

// 🔴 REALTIME: Broadcasting authentication routes
Broadcast::routes(["middleware" => ["auth"]]);

// Authenticated user routes
Route::middleware(["auth"])->group(function () {
    // Checkout page (requires auth)
    Route::get("/checkout", function () {
        return view("checkout");
    })->name("checkout");

    // 🔴 REALTIME: Test Echo page
    Route::get("/test-echo", function () {
        return view("test-echo");
    })->name("test.echo");

    // 🔴 REALTIME: Simple test page
    Route::get("/test-simple", function () {
        return view("test-simple");
    })->name("test.simple");

    // Order routes
    Route::post("/orders", [OrderController::class, "store"])->name(
        "orders.store",
    );
    Route::get("/order-status", [OrderController::class, "status"])->name(
        "order.status",
    );
    Route::get("/order-history", [OrderController::class, "history"])->name(
        "order.history",
    );

    // Profile routes
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // Review routes
    Route::prefix("reviews")
        ->name("reviews.")
        ->group(function () {
            Route::get("/my-reviews", [
                ReviewController::class,
                "myReviews",
            ])->name("my-reviews");
            Route::get("/create/{order}", [
                ReviewController::class,
                "create",
            ])->name("create");
            Route::post("/store/{order}", [
                ReviewController::class,
                "store",
            ])->name("store");
            Route::get("/{review}", [ReviewController::class, "show"])->name(
                "show",
            );
            Route::put("/{review}", [ReviewController::class, "update"])->name(
                "update",
            );
            Route::delete("/{review}", [
                ReviewController::class,
                "destroy",
            ])->name("destroy");
        });

    // Loyalty Program routes
    Route::prefix("loyalty")
        ->name("loyalty.")
        ->group(function () {
            Route::get("/", [LoyaltyController::class, "index"])->name("index");
            Route::get("/rewards", [LoyaltyController::class, "rewards"])->name(
                "rewards",
            );
            Route::get("/rewards/{reward}", [
                LoyaltyController::class,
                "showReward",
            ])->name("reward.show");
            Route::post("/rewards/{reward}/redeem", [
                LoyaltyController::class,
                "redeem",
            ])->name("redeem");
            Route::get("/redemption/{redemption}", [
                LoyaltyController::class,
                "showRedemption",
            ])->name("redemption");
            Route::post("/redemption/{redemption}/cancel", [
                LoyaltyController::class,
                "cancelRedemption",
            ])->name("redemption.cancel");
            Route::get("/redemption-history", [
                LoyaltyController::class,
                "redemptionHistory",
            ])->name("redemption-history");
            Route::get("/points-history", [
                LoyaltyController::class,
                "pointsHistory",
            ])->name("points-history");
            Route::get("/tiers", [LoyaltyController::class, "tiers"])->name(
                "tiers",
            );
        });
});

// Admin routes
Route::middleware(["auth", "isAdmin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("/dashboard", [AdminOrderController::class, "index"])->name(
            "dashboard",
        );
        Route::patch("/orders/{order}/status", [
            AdminOrderController::class,
            "updateStatus",
        ])->name("orders.updateStatus");
        Route::patch("/orders/{order}/estimated-time", [
            AdminOrderController::class,
            "updateEstimatedTime",
        ])->name("orders.updateEstimatedTime");
        Route::delete("/orders/{order}", [
            AdminOrderController::class,
            "destroy",
        ])->name("orders.destroy");

        // Menu management routes
        Route::resource("menus", AdminMenuController::class);

        // Promo management routes
        Route::resource(
            "promos",
            \App\Http\Controllers\Admin\PromoController::class,
        );

        // News management routes
        Route::resource(
            "news",
            \App\Http\Controllers\Admin\NewsController::class,
        );

        // Contact messages management
        Route::resource(
            "contacts",
            \App\Http\Controllers\Admin\ContactController::class,
        )->only(["index", "show", "destroy"]);

        // Review management routes
        Route::prefix("reviews")
            ->name("reviews.")
            ->group(function () {
                Route::get("/", [AdminReviewController::class, "index"])->name(
                    "index",
                );
                Route::get("/{review}", [
                    AdminReviewController::class,
                    "show",
                ])->name("show");
                Route::post("/{review}/approve", [
                    AdminReviewController::class,
                    "approve",
                ])->name("approve");
                Route::post("/{review}/reject", [
                    AdminReviewController::class,
                    "reject",
                ])->name("reject");
                Route::delete("/{review}", [
                    AdminReviewController::class,
                    "destroy",
                ])->name("destroy");
                Route::post("/bulk-approve", [
                    AdminReviewController::class,
                    "bulkApprove",
                ])->name("bulk-approve");
                Route::post("/bulk-reject", [
                    AdminReviewController::class,
                    "bulkReject",
                ])->name("bulk-reject");
                Route::post("/bulk-destroy", [
                    AdminReviewController::class,
                    "bulkDestroy",
                ])->name("bulk-destroy");
            });

        // Loyalty management routes
        Route::prefix("loyalty")
            ->name("loyalty.")
            ->group(function () {
                Route::get("/", [AdminLoyaltyController::class, "index"])->name(
                    "index",
                );

                // Rewards management
                Route::get("/rewards", [
                    AdminLoyaltyController::class,
                    "rewards",
                ])->name("rewards");
                Route::get("/rewards/create", [
                    AdminLoyaltyController::class,
                    "createReward",
                ])->name("rewards.create");
                Route::post("/rewards", [
                    AdminLoyaltyController::class,
                    "storeReward",
                ])->name("rewards.store");
                Route::get("/rewards/{reward}/edit", [
                    AdminLoyaltyController::class,
                    "editReward",
                ])->name("rewards.edit");
                Route::put("/rewards/{reward}", [
                    AdminLoyaltyController::class,
                    "updateReward",
                ])->name("rewards.update");
                Route::delete("/rewards/{reward}", [
                    AdminLoyaltyController::class,
                    "destroyReward",
                ])->name("rewards.destroy");

                // Redemptions management
                Route::get("/redemptions", [
                    AdminLoyaltyController::class,
                    "redemptions",
                ])->name("redemptions");
                Route::post("/redemptions/{redemption}/mark-used", [
                    AdminLoyaltyController::class,
                    "markRedemptionUsed",
                ])->name("redemptions.mark-used");
                Route::post("/redemptions/{redemption}/cancel", [
                    AdminLoyaltyController::class,
                    "cancelRedemption",
                ])->name("redemptions.cancel");

                // Tiers management
                Route::get("/tiers", [
                    AdminLoyaltyController::class,
                    "tiers",
                ])->name("tiers");
                Route::put("/tiers/{tier}", [
                    AdminLoyaltyController::class,
                    "updateTier",
                ])->name("tiers.update");

                // Points management
                Route::post("/award-points", [
                    AdminLoyaltyController::class,
                    "awardPoints",
                ])->name("award-points");
                Route::post("/deduct-points", [
                    AdminLoyaltyController::class,
                    "deductPoints",
                ])->name("deduct-points");
                Route::get("/user/{user}", [
                    AdminLoyaltyController::class,
                    "userDetails",
                ])->name("user-details");
                Route::get("/transactions", [
                    AdminLoyaltyController::class,
                    "transactions",
                ])->name("transactions");
            });

        // Report routes
        Route::prefix("reports")
            ->name("reports.")
            ->group(function () {
                Route::get("/", [ReportController::class, "index"])->name(
                    "index",
                );

                // Orders export
                Route::get("/orders/excel", [
                    ReportController::class,
                    "exportOrdersExcel",
                ])->name("orders.excel");
                Route::get("/orders/pdf", [
                    ReportController::class,
                    "exportOrdersPdf",
                ])->name("orders.pdf");

                // Revenue export
                Route::get("/revenue/excel", [
                    ReportController::class,
                    "exportRevenueExcel",
                ])->name("revenue.excel");
                Route::get("/revenue/pdf", [
                    ReportController::class,
                    "exportRevenuePdf",
                ])->name("revenue.pdf");

                // Users export
                Route::get("/users/excel", [
                    ReportController::class,
                    "exportUsersExcel",
                ])->name("users.excel");
                Route::get("/users/pdf", [
                    ReportController::class,
                    "exportUsersPdf",
                ])->name("users.pdf");

                // Daily summary (API-like)
                Route::get("/daily-summary", [
                    ReportController::class,
                    "dailySummary",
                ])->name("daily-summary");
            });
    });

require __DIR__ . "/auth.php";
