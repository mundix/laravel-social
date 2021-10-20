<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes(['logout' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.show');

Route::get('/logout', [App\Http\Controllers\AuthLoginController::class, 'logout'])->name('logout');

Route::get('reset/invite', [App\Http\Controllers\AuthLoginController::class, 'resetInvite'])->name('reset.invite');

Route::get('company/employee/invite/{token}',
    [App\Http\Controllers\AuthLoginController::class, 'invite'])->name('company.invite');

Route::get('company/invite/{token}',
    [App\Http\Controllers\Admin\AdminController::class, 'invite'])->name('admin.invite');


Route::group(['middleware' => ['auth']], function () {

    Route::resource('employees', App\Http\Controllers\EmployeeController::class);

    Route::resource('companies', App\Http\Controllers\CompanyController::class);
});

# Users or employee routes
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {

    Route::get('/login', App\Http\Livewire\Users\Auth\LoginComponent::class)->name('login');

    Route::group(['middleware' => ['employees.only']], function () {

        Route::get('/signup', App\Http\Livewire\Users\Auth\RegisterComponent::class)->name('signup');

        Route::get('/onboarding', App\Http\Livewire\Users\UserOnboardingComponent::class)->name('onboarding');

    });

    Route::group(['middleware' => ['employees.auth', 'employees.only']], function () {

        Route::get('/favorites', [App\Http\Controllers\EmployeeController::class, 'favorites'])->name("favorites");

        Route::get('/profile', [App\Http\Controllers\Users\ProfileController::class, 'index'])->name('profile');

        Route::get('/profile/edit',
            [App\Http\Controllers\Users\ProfileController::class, 'edit'])->name('profile.edit');
    });
});

# Company routes
Route::group(['prefix' => 'company', 'as' => 'company.'], function () {

    Route::get('/login', App\Http\Livewire\Companies\Auth\CompanyLoginComponent::class)->name('login');

    Route::get('/signup', App\Http\Livewire\Companies\Auth\CompanyRegisterComponent::class)->name('signup');

    Route::group(['middleware' => [ 'companies.only']], function () {
        Route::get('/onboarding', [App\Http\Controllers\CompanyController::class, 'onboarding'])->name('onboarding');
        Route::get('/signup/onboarding', [App\Http\Controllers\CompanyController::class, 'signup'])->name('onboarding.signup');
    });

    Route::group(['middleware' => ['companies.auth', 'companies.only']], function () {

        Route::get('/profile', [App\Http\Controllers\Companies\ProfileController::class, 'index'])->name('profile');

        Route::get('/employees', [App\Http\Controllers\CompanyController::class, 'employees'])->name('employees');

        Route::get('/news/{id}/edit', [App\Http\Controllers\CompanyController::class, 'edit_news'])->name('edit.news');
        Route::get('/news/{id}/preview', [App\Http\Controllers\CompanyController::class, 'news_preview'])->name('preview.news');
        Route::get('/news/create', [App\Http\Controllers\CompanyController::class, 'create_news'])->name('create.news');

        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::get('index', [App\Http\Controllers\Admin\CompanyController::class, 'index'])->name('index');
            Route::get('causes', [App\Http\Controllers\Admin\CompanyController::class, 'causes'])->name('causes');
            Route::get('involvements', [App\Http\Controllers\Admin\CompanyController::class, 'involvements'])->name('involvements');
            Route::get('stories', [App\Http\Controllers\Admin\CompanyController::class, 'stories'])->name('stories');
            Route::get('news', [App\Http\Controllers\Admin\CompanyController::class, 'news'])->name('news');
            Route::get('events', [App\Http\Controllers\Admin\CompanyController::class, 'events'])->name('events');
            Route::get('employees', [App\Http\Controllers\Admin\CompanyController::class, 'employees'])->name('employees');
            Route::get('users', [App\Http\Controllers\Admin\CompanyController::class, 'users'])->name('users');
            Route::get('profile', [App\Http\Controllers\Admin\CompanyController::class, 'profile'])->name('profile');
        });
    });

    Route::get('/{slug}', [App\Http\Controllers\CompanyController::class, 'company'])->name('slug');

    Route::get('/{slug}/news', [App\Http\Controllers\CompanyController::class, 'news'])->name('news');
    Route::get('/{CompanySlug}/news/{slug}', [App\Http\Controllers\CompanyController::class, 'news_detail'])->name('news.show');
    Route::get('/{CompanySlug}/employee/{slug}', [App\Http\Controllers\CompanyController::class, 'employee'])->name('employee.show');

    Route::get('/{slug}/causes', [App\Http\Controllers\CompanyController::class, 'causes'])->name('causes');

    Route::get('/employee/{slug}', [App\Http\Controllers\CompanyController::class, 'employees'])->name('employee.slug');



});

# Admin route
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::group(['middleware' => ['admin.auth', 'admin.only']], function () {

        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');

        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

        Route::get('/companies', [App\Http\Controllers\Admin\AdminController::class, 'companies'])->name('companies');

        Route::get('/causes', [App\Http\Controllers\Admin\AdminController::class, 'causes'])->name('causes');

        Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');

        Route::get('/profile/edit',
            [App\Http\Controllers\Companies\ProfileController::class, 'edit'])->name('profile.edit');
    });
});

Route::get('/{slug}/causes', [App\Http\Controllers\CauseController::class, 'index'])->name('causes.slug');

Route::get('login/okta', [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])->name('login-okta');
Route::get('login/okta/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);
