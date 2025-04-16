@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Upravljanje korisnicima</h1>
            </div>
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ime</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uloga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcije</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="nastavnik" {{ $user->role == 'nastavnik' ? 'selected' : '' }}>Nastavnik</option>
                                                <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection