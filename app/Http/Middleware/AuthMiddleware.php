<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle request yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $role
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('beranda')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Cek jika ada role yang di-require
            if ($role) {
                try {
                    $userRole = Auth::user()->role;
                    $activeRole = session('active_role') ?? $userRole;
                } catch (\Exception $e) {
                    // Jika terjadi error saat mengakses session atau user, logout dan redirect
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('beranda')
                        ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
                }
                
                // Jika user role atau active_role tidak sesuai dengan role yang diakses,
                // redirect ke dashboard sesuai active_role yang sedang aktif (jangan logout)
                if ($userRole !== $role || ($activeRole && $activeRole !== $role)) {
                    // Redirect ke dashboard sesuai active_role atau userRole
                    $redirectRole = $activeRole ?? $userRole;
                    
                    if ($redirectRole === 'admin') {
                        return redirect()->route('admin.dashboard')
                            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                    } elseif ($redirectRole === 'pemilik') {
                        return redirect()->route('pemilik.dashboard')
                            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                    } elseif ($redirectRole === 'pencari') {
                        return redirect()->route('pencari.beranda')
                            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                    }
                }
                
                // Pastikan active_role sesuai dengan role yang diakses
                try {
                    if (!$activeRole || $activeRole !== $role) {
                        session(['active_role' => $role]);
                    }
                } catch (\Exception $e) {
                    // Jika session tidak bisa di-set, logout dan redirect
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('beranda')
                        ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
                }
            }

            return $next($request);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Handle CSRF token mismatch
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('beranda')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        } catch (\Exception $e) {
            // Handle other session-related errors
            if (str_contains($e->getMessage(), 'session') || str_contains($e->getMessage(), 'Session')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('beranda')
                    ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
            }
            
            // Re-throw jika bukan session error
            throw $e;
        }
    }
}
