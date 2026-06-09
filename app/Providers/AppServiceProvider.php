<?php

namespace App\Providers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('components.sidebar-reseller', function ($view) {
            $pendingPaymentCount = 0;

            if (Auth::check() && Auth::user()->role === 'reseller' && Auth::user()->reseller) {
                $resellerId = Auth::user()->reseller->id;
                $pendingPaymentCount = Invoice::whereHas('pesanan', function ($query) use ($resellerId) {
                    $query->where('reseller_id', $resellerId);
                })
                ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
                ->count();
            }

            $view->with('pendingPaymentCount', $pendingPaymentCount);
        });
    }
}
