@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-white shadow-xl rounded-2xl px-12 py-16 max-w-2xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Bok, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 text-lg">Dobrodošao natrag 👋</p>
        </div>
    </div>
@endsection
