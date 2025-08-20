<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        view()->composer('*', function ($view)
        {
            $view->with('departments', Department::orderBy('id', 'asc')->take(8)->get());
        });

        view()->composer('*', function ($view)
        {
            $view->with('randomProducts', Product::inRandomOrder()->take(8)->get());
        });

        View::composer('*', function ($view)
        {
            $cartCount = 0;
            if (Auth::check())
            {
                $cartCount = Cart::where('user_id', Auth::id())->count();
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
