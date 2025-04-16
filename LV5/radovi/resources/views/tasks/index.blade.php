@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Popis radova</h1>
            </div>
            @if (session('success'))
                <div class="mx-6 my-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naslov (HR)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naslov (EN)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tip studija</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nastavnik</th>
                                @if (Auth::user()->role === 'student')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tasks as $task)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->title_hr }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->title_en }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $task->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->study_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $task->user->name }}</td>
                                    @if (Auth::user()->role === 'student')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $alreadyApplied = $task->applications->contains('student_id', Auth::id());
                                    @endphp

                                    @if (!$alreadyApplied)
                                        <form action="{{ route('tasks.apply', $task) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                style="background-color: #16a34a; color: white;" {{-- zeleno za prijavu --}}
                                                class="inline-flex items-center px-4 py-2 font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                Prijavi se
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('tasks.withdraw', $task) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background-color: #dc2626; color: white;" {{-- crveno za odjavu --}}
                                                class="inline-flex items-center px-4 py-2 font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                Odjavi se
                                            </button>
                                        </form>
                                    @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection