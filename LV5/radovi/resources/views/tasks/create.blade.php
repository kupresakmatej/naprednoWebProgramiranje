@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.add_task') }}</h1>
            </div>
            <div class="flex justify-between items-center" style="margin-top: 1rem;">
                <div class="space-x-2">
                    <a href="{{ route('locale.switch', ['locale' => 'hr']) }}"
                    style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #4B5563; transition: background-color 0.3s ease-in-out; 
                            {{ app()->getLocale() === 'hr' ? 'font-weight: bold; background-color: #2563EB; color: white;' : 'hover:bg-gray-100;' }}">
                        HR
                    </a>
                    <span style="color: #4B5563;">|</span>
                    <a href="{{ route('locale.switch', ['locale' => 'en']) }}"
                    style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #4B5563; transition: background-color 0.3s ease-in-out; 
                            {{ app()->getLocale() === 'en' ? 'font-weight: bold; background-color: #2563EB; color: white;' : 'hover:bg-gray-100;' }}">
                        EN
                    </a>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('tasks.create') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="title_hr" class="block text-sm font-medium text-gray-700">{{ __('messages.title_hr') }}</label>
                        <input type="text" name="title_hr" id="title_hr" value="{{ old('title_hr') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('title_hr') border-red-500 @enderror">
                        @error('title_hr')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="title_en" class="block text-sm font-medium text-gray-700">{{ __('messages.title_en') }}</label>
                        <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('title_en') border-red-500 @enderror">
                        @error('title_en')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('messages.description') }}</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="study_type" class="block text-sm font-medium text-gray-700">{{ __('messages.study_type') }}</label>
                        <select name="study_type" id="study_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('study_type') border-red-500 @enderror">
                            <option value="">{{ __('messages.study_type') }}</option>
                            @foreach([
                                'stručni' => __('messages.study_type_options.stručni'),
                                'preddiplomski' => __('messages.study_type_options.preddiplomski'),
                                'diplomski' => __('messages.study_type_options.diplomski'),
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('study_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('study_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            style="background-color: #2563eb; color: white;"
                            class="inline-flex items-center px-6 py-3 font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ __('messages.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
