<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Redirect ke beranda dengan pesan error, bukan ke route login yang tidak ada
        if ($request->expectsJson()) {
            return null;
        }
        
        // Redirect ke beranda dengan pesan bahwa session telah berakhir
        return route('beranda');
    }
    
    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        // Redirect ke beranda dengan pesan yang jelas
        return redirect()->route('beranda')
            ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
    }
}
