@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-6">
        <a href="{{ route('projects.index') }}" class="inline-block text-lg px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition duration-300 ease-in-out shadow-lg"
            class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
            style="background-color:#3182ce !important;">
            Nazad na projekte
        </a>
    </div>

    <div class="text-center mb-10">
        <h1 class="text-6xl font-semibold text-gray-800">{{ Auth::user()->name }}</h1>
    </div>

    <div class="mt-10">
        <h3 class="text-xl font-semibold">Projekti gdje sam voditelj</h3>
        @if($ledProjects->isEmpty())
            <p>Nema projekata gdje ste voditelj.</p>
        @else
            <ul class="list-disc pl-5 mt-2">
                @foreach($ledProjects as $project)
                    <li>{{ $project->name }} ({{ $project->start_date }} - {{ $project->end_date }})</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-6">
        <h3 class="text-xl font-semibold">Projekti gdje sam član</h3>
        @if($memberProjects->isEmpty())
            <p>Nema projekata gdje ste član.</p>
        @else
            <ul class="list-disc pl-5 mt-2">
                @foreach($memberProjects as $project)
                    <li>{{ $project->name }} ({{ $project->start_date }} - {{ $project->end_date }})</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
