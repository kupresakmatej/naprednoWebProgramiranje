@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Prijave za vaše radove</h1>
            </div>
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif
                @foreach ($tasks as $task)
                    <div class="mb-12 pb-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $task->title_hr }}</h2>
                        @if ($task->applications->isEmpty())
                            <p class="text-gray-500 italic">Nema prijava za ovaj rad.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($task->applications as $application)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->student->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if ($application->accepted)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Prihvaćen
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Čeka
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if ($application->accepted)
                                                    <form action="{{ route('tasks.reject', $application) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        style="background-color: #d97706; color: white;" {{-- yellow-600 --}}
                                                        class="inline-flex items-center px-4 py-2 font-semibold rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                        Poništi prihvaćanje
                                                    </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('tasks.accept', $application) }}" method="POST">
                                                    @csrf
                                                    @php
                                                        $alreadyAccepted = $task->applications->where('accepted', true)->isNotEmpty();
                                                    @endphp
                                                    <button type="submit"
                                                        style="{{ $alreadyAccepted ? 'background-color: #16a34a; color: white; opacity: 0.5; cursor: not-allowed;' : 'background-color: #16a34a; color: white;' }}"
                                                        class="inline-flex items-center px-4 py-2 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                        {{ $alreadyAccepted ? 'disabled title=Već je prihvaćen student za ovaj rad' : '' }}>
                                                        Prihvati
                                                    </button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection