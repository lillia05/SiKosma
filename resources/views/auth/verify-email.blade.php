@extends('layouts.app')

@section('title', 'Verifikasi Email - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 font-poppins mb-2">Verifikasi Email Anda</h2>
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-poppins">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if (session('warning'))
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-800 font-poppins">{{ session('warning') }}</p>
                    </div>
                @endif
                
                @if (session('message'))
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800 font-poppins">{{ session('message') }}</p>
                    </div>
                @endif
                
                <p class="text-gray-600 font-poppins mb-6">
                    Terima kasih telah mendaftar! Kami telah mengirimkan link verifikasi ke email <strong>{{ Auth::user()->email }}</strong>.
                </p>
                
                <p class="text-sm text-gray-600 font-poppins mb-6">
                    Silakan cek inbox email Anda dan klik link verifikasi untuk mengaktifkan akun. Jika email tidak muncul, cek juga folder spam.
                </p>
                
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full bg-yellow-400 text-blue-900 font-bold py-3 rounded-lg hover:bg-yellow-500 transition font-poppins">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-blue-900 text-sm hover:underline font-poppins">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
