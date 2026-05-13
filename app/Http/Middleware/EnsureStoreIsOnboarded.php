<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreIsOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $store = Store::current();

        if (! $store || ! $store->isOnboarded()) {
            return redirect()
                ->route('onboarding')
                ->with('warning', 'Selesaikan onboarding toko terlebih dahulu sebelum masuk ke dashboard.');
        }

        return $next($request);
    }
}
