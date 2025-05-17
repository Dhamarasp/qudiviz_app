@extends('app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Konten Sub Pelajaran</h1>
        <a href="{{ isset($selectedSubLesson) ? route('admin.sub-lesson-contents', ['subLessonId' => $selectedSubLesson->id]) : route('admin.sub-lesson-contents') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.sub-lesson-contents.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="sub_lesson_id" class="block text-sm font-medium text-gray-700 mb-2">Sub Pelajaran</label>
                <select id="sub_lesson_id" name="sub_lesson_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="">Pilih Sub Pelajaran</option>
                    @foreach($subLessons as $subLesson)
                        <option value="{{ $subLesson->id }}" {{ (isset($selectedSubLesson) && $selectedSubLesson->id == $subLesson->id) ? 'selected' : '' }}>
                            {{ $subLesson->name }} ({{ $subLesson->lesson->name }})
                        </option>
                    @endforeach
                </select>
                @error('sub_lesson_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten</label>
                <select id="content_type" name="content_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <option value="">Pilih Tipe Konten</option>
                    <option value="text">Teks</option>
                    <option value="image">Gambar</option>
                    <option value="video">Video</option>
                    <option value="audio">Audio</option>
                    <option value="exercise">Latihan</option>
                </select>
                @error('content_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Konten</label>
                <textarea id="content" name="content" rows="6" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                <p class="text-xs text-gray-500 mt-1">
                    Untuk tipe konten gambar, video, atau audio, masukkan URL. Untuk tipe konten latihan, masukkan JSON.
                </p>
                @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <input type="number" id="order" name="order" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                @error('order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan Konten
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
