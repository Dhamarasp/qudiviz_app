@extends('app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            @if(isset($subLesson))
                Konten Sub Pelajaran: {{ $subLesson->name }}
            @else
                Semua Konten Sub Pelajaran
            @endif
        </h1>
        <div>
            @if(isset($subLesson))
                <a href="{{ route('admin.sub-lesson-contents.create', ['subLessonId' => $subLesson->id]) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Tambah Konten Baru
                </a>
                <a href="{{ route('admin.sub-lessons') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2">
                    Kembali ke Sub Pelajaran
                </a>
            @else
                <a href="{{ route('admin.sub-lesson-contents.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Tambah Konten Baru
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2">
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    @if(!isset($subLesson))
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sub Pelajaran
                    </th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipe Konten
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Konten (Preview)
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Urutan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($contents as $content)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $content->id }}
                    </td>
                    @if(!isset($subLesson))
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $content->subLesson->name }}
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($content->content_type == 'text') bg-blue-100 text-blue-800
                            @elseif($content->content_type == 'image') bg-purple-100 text-purple-800
                            @elseif($content->content_type == 'video') bg-red-100 text-red-800
                            @elseif($content->content_type == 'audio') bg-yellow-100 text-yellow-800
                            @elseif($content->content_type == 'exercise') bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($content->content_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                        <div class="truncate">
                            @if($content->content_type == 'text')
                                {{ Str::limit($content->content, 100) }}
                            @elseif($content->content_type == 'image')
                                <img src="{{ $content->content }}" alt="Image Preview" class="h-10 w-auto">
                            @elseif($content->content_type == 'video')
                                <span class="text-blue-500">{{ Str::limit($content->content, 50) }}</span>
                            @elseif($content->content_type == 'audio')
                                <span class="text-yellow-500">{{ Str::limit($content->content, 50) }}</span>
                            @elseif($content->content_type == 'exercise')
                                <span class="text-green-500">{{ Str::limit($content->content, 50) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $content->order }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.sub-lesson-contents.edit', $content->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.sub-lesson-contents.delete', $content->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ isset($subLesson) ? '5' : '6' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        Tidak ada konten sub pelajaran yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contents->links() }}
    </div>
</div>
@endsection
