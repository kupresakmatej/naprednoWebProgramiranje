@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow-lg rounded-2xl max-w-xl mx-auto" style="padding: 3rem;">
            <h1 class="text-2xl font-bold text-gray-800 mb-8">Uredi profil</h1>

            @if (session('success'))
                <div class="mb-6 px-4 py-3 rounded bg-green-100 text-green-700 border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ime</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit"
                            style="padding: 0.75rem 1.5rem; background-color: #2563eb; color: white; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; border: none; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='#1d4ed8'"
                            onmouseout="this.style.backgroundColor='#2563eb'">
                        Spremi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
