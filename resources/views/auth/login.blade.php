@extends('layouts.app')

@section('title', 'Login - E-Resep')

@section('content')
<div class="fixed inset-0 bg-white z-50 flex items-center justify-center p-4">
  <div class="max-w-md w-full">
    <div class="bg-white rounded-2xl shadow-2xl p-8">
      <div class="text-center mb-8">
        <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i data-lucide="stethoscope" class="w-10 h-10 text-primary"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">E-Resep</h1>
        <p class="text-gray-600">Sistem Pemberian Resep Obat</p>
      </div>

      @if(session('error'))
      <div class="mb-4 p-4 bg-error-lighter border border-error-light text-error-dark rounded-xl">
        {{ session('error') }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
          <input type="text" name="username" id="username" required
            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
            placeholder="dokter@email.com" value="{{ old('username') }}">
          @error('username')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
            placeholder="••••••••">
          @error('password')
            <p class="text-error text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
          </label>
          <a href="#" class="text-sm text-primary hover:underline">Lupa password?</a>
        </div>

        <button type="submit" id="login-btn"
          class="w-full mt-6 px-4 py-3 bg-primary text-white rounded-xl font-medium hover:bg-primary-hover transition-all duration-200 cursor-pointer flex items-center justify-center">
          <span>Masuk</span>
          <div id="login-spinner"
            class="hidden animate-spin ml-2 h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
        </button>
      </form>

      <div class="mt-6 pt-6 border-t border-gray-200" style="display: none">
        <p class="text-center text-sm text-gray-600">
          Belum punya akun?
          <a href="#" class="text-primary font-medium hover:underline">Hubungi administrator</a>
        </p>
      </div>

      <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">© 2026 E-Resep.</p>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
    
    const loginForm = document.querySelector('form');
    const loginBtn = document.getElementById('login-btn');
    const loginSpinner = document.getElementById('login-spinner');
    
    if (loginForm) {
      loginForm.addEventListener('submit', function () {
        loginBtn.disabled = true;
        loginSpinner.classList.remove('hidden');
        loginBtn.querySelector('span').textContent = 'Memproses...';
      });
    }
  });
</script>
@endpush
@endsection