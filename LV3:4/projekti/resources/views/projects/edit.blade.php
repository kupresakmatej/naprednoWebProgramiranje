@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Uredi projekt</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Naziv projekta:</label>
            <input type="text" name="name" value="{{ old('name', $project->name) }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Opis projekta:</label>
            <textarea name="description" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" rows="3">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Cijena projekta (HRK):</label>
            <input type="number" name="price" value="{{ old('price', $project->price) }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" step="0.01">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Poslovi:</label>
            <div id="tasksContainer">
                @foreach(json_decode($project->tasks) as $task)
                    <input type="text" name="tasks[]" value="{{ $task }}" class="w-full p-3 border rounded-lg mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" placeholder="Unesi posao">
                @endforeach
                @if(!empty(json_decode($project->tasks)))
                    <button type="button" id="addTask" 
                    class="inline-block mt-4 text-lg px-1 py-1 bg-blue-600 text-white rounded-md 
                        hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                        transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                    style="background-color: #3182ce !important;">
                        Dodaj još jedan posao
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Datum početka:</label>
                <input type="date" name="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Datum završetka:</label>
                <input type="date" name="end_date" value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-black font-medium px-4 py-2 text-sm rounded shadow-sm"
            class="inline-block mt-4 text-lg px-2 py-4 bg-blue-600 text-white rounded-md 
                hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
            style="background-color:#3182ce !important;">
            Spremi promjene
        </button>
    </form>
</div>

<script>
    document.getElementById('addTask').addEventListener('click', function() {
        const newTaskInput = document.createElement('input');
        newTaskInput.type = 'text';
        newTaskInput.name = 'tasks[]';
        newTaskInput.classList.add('w-full', 'p-3', 'border', 'rounded-lg', 'mb-2', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500', 'transition');
        newTaskInput.placeholder = 'Unesi posao';

        document.getElementById('tasksContainer').appendChild(newTaskInput);
    });
</script>
@endsection
