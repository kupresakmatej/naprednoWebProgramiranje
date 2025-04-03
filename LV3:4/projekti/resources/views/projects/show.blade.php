@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-8">
        <a href="{{ route('projects.index') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 text-sm rounded transition shadow-sm font-medium flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a6 6 0 110 12 6 6 0 010-12z" />
            </svg>
            Povratak na projekte
        </a>
    </div>

    <div class="bg-gray-50 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-6">
            <h1 class="text-3xl font-bold text-black">{{ $project->name }}</h1>
            <p class="text-black mt-2">{{ $project->description }}</p>
        </div>

        <div>
            <!-- Edit Button (For Project Leader) -->
            @if (Auth::id() === $project->leader_id)
            <div class="mb-8 p-6">
                <a href="{{ route('projects.edit', $project->id) }}" 
                    class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                        hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                        transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                    style="background-color:rgba(0, 0, 0, 0.49) !important;">
                    Uredi projekt
                </a>
            </div>
            @endif

            <!-- Team Members Section -->
            <div class="mb-8 bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-black mb-4 border-b border-gray-200 pb-2">Članovi tima</h2>
                <ul class="space-y-2">
                    @foreach($members as $member)
                    <li class="flex justify-between items-center py-2 px-4 bg-blue-50 hover:bg-blue-100 rounded-md transition">
                        <span class="font-medium text-black">{{ $member->name }}</span>
                        @if($member->id == $project->leader_id)
                        <span class="bg-blue-100 text-black px-3 py-1 rounded-full text-xs font-semibold">Voditelj</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Add Member Section -->
            <div class="mb-8 bg-amber-50 rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-black mb-4">Dodaj člana</h3>
                <form action="{{ route('projects.addMember', $project->id) }}" method="POST">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0">
                        <select name="user_id" class="border border-gray-300 p-2 rounded-lg sm:w-2/3 focus:ring-blue-500 focus:border-blue-500 bg-white text-black" required>
                            <option value="">Odaberite korisnika</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        
                        @if (Auth::id() === $project->leader_id) <!-- Check if user is project leader -->
                            <button type="submit" 
                                class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                                    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                                    transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                                style="background-color: #3182ce !important;">
                                Dodaj člana
                            </button>
                        @else
                            <button type="button" class="bg-gray-300 text-black font-medium px-4 py-2 text-sm rounded shadow-sm cursor-not-allowed" disabled>
                                Niste voditelj
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tasks Section -->
            <div class="bg-blue-50 rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-black mb-4">Poslovi</h3>
                <form action="{{ route('projects.updateTasks', $project->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <ul class="space-y-2 mb-6">
                        @foreach(json_decode($project->tasks) as $task)
                        <li class="flex items-center bg-white p-3 rounded-md">
                            <input type="checkbox" name="completed_tasks[]" value="{{ $task }}"
                                {{ in_array($task, json_decode($project->completed_tasks ?? '[]', true)) ? 'checked' : '' }}
                                class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-black">{{ $task }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-black font-medium px-4 py-2 text-sm rounded shadow-sm"
                        class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                            transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                        style="background-color:#3182ce !important;">
                        Spremi obavljene poslove
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
