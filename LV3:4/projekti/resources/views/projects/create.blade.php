@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Kreiraj novi projekt</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Naziv projekta:</label>
            <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Opis projekta:</label>
            <textarea name="description" class="w-full p-2 border rounded-lg" rows="3"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Cijena projekta (EUR):</label>
            <input type="number" name="price" class="w-full p-2 border rounded-lg" step="0.01">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Obavljeni poslovi:</label>
            <div id="tasksContainer">
                <input type="text" name="tasks[]" class="w-full p-2 border rounded-lg mb-2" placeholder="Unesi posao">
            </div>
            <button type="button" id="addTask" class="bg-blue-500 text-black p-3 rounded-lg hover:bg-blue-600 transition"
                class="inline-block mt-4 text-3xl px-2 py-4 bg-blue-600 text-white rounded-md 
                    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                    transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
                style="background-color: #3182ce !important;">
                Dodaj još jedan posao
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Datum početka:</label>
                <input type="date" name="start_date" class="w-full p-2 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Datum završetka:</label>
                <input type="date" name="end_date" class="w-full p-2 border rounded-lg" required>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-black p-3 rounded-lg hover:bg-blue-600 transition"
            class="inline-block mt-4 text-3xl px-2 py-4 bg-blue-600 text-white rounded-md 
                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 
                   transition duration-300 ease-in-out shadow-lg transform hover:scale-105" 
            style="background-color: #3182ce !important;">
            Kreiraj projekt
        </button>
    </form>
</div>

<script>
    // Dodavanje novih polja za unos poslova
    document.getElementById('addTask').addEventListener('click', function() {
        const newTaskInput = document.createElement('input');
        newTaskInput.type = 'text';
        newTaskInput.name = 'tasks[]';
        newTaskInput.classList.add('w-full', 'p-2', 'border', 'rounded-lg', 'mb-2');
        newTaskInput.placeholder = 'Unesi posao';

        document.getElementById('tasksContainer').appendChild(newTaskInput);
    });
</script>
@endsection
