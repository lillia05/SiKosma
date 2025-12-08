<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle session-related exceptions
        if ($exception instanceof \Illuminate\Session\TokenMismatchException ||
            (str_contains($exception->getMessage(), 'session') || 
             str_contains($exception->getMessage(), 'Session') ||
             str_contains($exception->getMessage(), '419'))) {
            
            // Logout user jika ada
            if (Auth::check()) {
                Auth::logout();
            }
            
            // Invalidate session
            try {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (\Exception $e) {
                // Ignore jika session sudah invalid
            }
            
            // Redirect ke beranda dengan pesan yang jelas
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session Anda telah berakhir. Silakan login kembali.',
                    'redirect' => route('beranda')
                ], 419);
            }
            
            return redirect()->route('beranda')
                ->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        }

        return parent::render($request, $exception);
    }
}
