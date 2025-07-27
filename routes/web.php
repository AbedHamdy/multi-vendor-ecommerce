<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Client\DepartmentController as ClientDepartmentController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PackagesController;
use App\Http\Controllers\Client\VendorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Vendor\DashboardVendorController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\SubmitDepartmentController;
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

// Route::get('/', function () {
//     return view('welcome');
// })->name("home");

Route::get("/", [HomeController::class, "index"])->name("home");

Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login/check", [LoginController::class, "check"])->name("check_credentials");
Route::get("/register/page", [LoginController::class, "registerPage"])->name("register_page");
Route::post("/register", [LoginController::class, "register"])->name("register");

// Route::post('/debug-login', function (\Illuminate\Http\Request $request) {
//     dd($request->all());
// })->name("debug-login");

Route::middleware(["admin"])->group(function(){
    // Dashboard
    Route::get("/dashboard/admin", [DashboardController::class, "index"])->name("dashboard");

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Package
    Route::get("/dashboard/admin/package", [PackageController::class, "index"])->name("package");
    Route::get("/dashboard/admin/package/create", [PackageController::class, "create"])->name("package.create");
    Route::post("/dashboard/admin/package/store", [PackageController::class, "store"])->name("package.store");
    Route::get("/dashboard/admin/package/show/{id}", [PackageController::class, "show"])->name("package.show");
    Route::get("/dashboard/admin/package/edit/{id}", [PackageController::class, "edit"])->name("package.edit");
    Route::put("/dashboard/admin/package/update/{id}", [PackageController::class, "update"])->name("package.update");
    Route::delete("/dashboard/admin/package/delete/{id}", [PackageController::class, "destroy"])->name("package.destroy");

    // Department
    Route::get("/dashboard/admin/department", [DepartmentController::class, "index"])->name("department");
    Route::get("/dashboard/admin/department/create", [DepartmentController::class, "create"])->name("department.create");
    Route::post("/dashboard/admin/department/store", [DepartmentController::class, "store"])->name("department.store");
    Route::get("/dashboard/admin/department/show/{id}", [DepartmentController::class, "show"])->name("department.show");
    Route::get("/dashboard/admin/department/edit/{id}", [DepartmentController::class, "edit"])->name("department.edit");
    Route::put("/dashboard/admin/department/update/{id}", [DepartmentController::class, "update"])->name("department.update");
    Route::delete("/dashboard/admin/department/delete/{id}", [DepartmentController::class, "destroy"])->name("department.destroy");

    // Attribute
    Route::get("/dashboard/admin/attribute/create/{id}", [AttributeController::class, "create"])->name("attribute.create");
    Route::post("/dashboard/admin/attribute/store", [AttributeController::class, "store"])->name("attribute.store");
    Route::get("/dashboard/admin/attribute/edit/{id}", [AttributeController::class, "edit"])->name("attribute.edit");
    Route::put("/dashboard/admin/attribute/update/{id}", [AttributeController::class, "update"])->name("attribute.update");
    Route::delete("/dashboard/admin/attribute/delete/{department}/{attribute}", [AttributeController::class, "destroy"])->name("attribute.destroy");
});

// Vendor
Route::middleware(["vendor"])->group(function(){
    // Choose Department
    Route::post("/choose/department", [SubmitDepartmentController::class, "store"])->name("department.submit");

    // Dashboard
    Route::get("/dashboard/vendor", [DashboardVendorController::class, "index"])->name("dashboard_vendor");
    Route::post("/logout/vendor", [LoginController::class, "logoutVendor"])->name("logout_vendor");

    // Product
    Route::get("/dashboard/vendor/all-products",[ProductController::class, "index"])->name("product");
    Route::get("/dashboard/vendor/product/create",[ProductController::class, "create"])->name("product.create");
    Route::post("/dashboard/vendor/product/store",[ProductController::class, "store"])->name("product.store");
    Route::get("/dashboard/vendor/product/show/{id}",[ProductController::class, "show"])->name("product.show");
    Route::get("/dashboard/vendor/product/edit/{id}",[ProductController::class, "edit"])->name("product.edit");
    Route::put("/dashboard/vendor/update/{id}",[ProductController::class, "update"])->name("product.update");
    Route::delete("/dashboard/vendor/product/delete/{id}",[ProductController::class, "destroy"])->name("product.delete");
});

// Client
Route::get("/all-packages", [PackagesController::class, "index"])->name("view_packages");
Route::get("/package/details/{id}", [PackagesController::class, "show"])->name("details_package");
Route::get("/package/subscribe/{id}", [PackagesController::class, "subscribe"])->name("subscribe_package");
Route::get("/all-departments", [ClientDepartmentController::class, "index"])->name("view_department");



Route::middleware(['user'])->group(function () {
    Route::get('/subscribe-package/{id}/pay', [StripeController::class, 'checkout'])->name('package.checkout');
    Route::get('/payment/success/{package_id}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

