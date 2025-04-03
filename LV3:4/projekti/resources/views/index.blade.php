@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Greeting and create project button -->
    <div class="text-center mb-6">
        <h1 class="text-5xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}!</h1>
        <a href="{{ route('projects.create') }}" 
            class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                   transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
            style="background-color: #3182ce !important;">
            Kreiraj novi projekt
        </a>
    </div>

    <!-- List of projects -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 p-4">
        @foreach ($projects as $project)
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold text-gray-800">{{ $project->name }}</h2>
                <p class="text-gray-600 mt-2">{{ Str::limit($project->description, 100) }}</p>
                <p class="text-gray-800 mt-4"><strong>Cijena:</strong> {{ $project->price }} EUR</p>
                <p class="text-gray-800 mt-2"><strong>Početak:</strong> {{ $project->start_date }}</p>
                <p class="text-gray-800"><strong>Završetak:</strong> {{ $project->end_date }}</p>

                <!-- Displaying members -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800">Članovi projekta:</h3>
                    <ul class="mt-2">
                        @foreach ($project->members as $member)
                            <li class="text-gray-800">
                                {{ $member->name }}
                                @if ($member->id == $project->leader_id)
                                    <span class="text-green-500 font-bold">(Voditelj)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Displaying tasks -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800">Poslovi koje treba obaviti:</h3>
                    <ul class="mt-2">
                        @foreach (json_decode($project->tasks) as $task)
                            <li class="flex items-center mt-2">
                                <input type="checkbox" disabled 
                                    {{ in_array($task, json_decode($project->completed_tasks ?? '[]', true)) ? 'checked' : '' }}
                                    class="mr-2" />
                                <span class="{{ in_array($task, json_decode($project->completed_tasks ?? '[]', true)) ? 'line-through text-gray-500' : 'text-gray-800' }}">{{ $task }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4 flex justify-between">
                    <a href="{{ route('projects.show', $project->id) }}" 
                    class="inline-block mt-4 text-lg px-1 py-1 bg-blue-600 text-white rounded-md 
                        hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                        transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                    style="background-color: #3182ce !important;">
                        Pogledaj projekt
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
