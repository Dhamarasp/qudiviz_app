@extends('app')

@section('content')
    <div class="container">
        <h1>Sub Lessons</h1>

        <a href="{{ route('admin.sub-lessons.create') }}" class="btn btn-primary mb-3">Create New Sub Lesson</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lesson</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subLessons as $subLesson)
                    <tr>
                        <td>{{ $subLesson->id }}</td>
                        <td>{{ $subLesson->lesson->title }}</td>
                        <td>{{ $subLesson->title }}</td>
                        <td>{{ $subLesson->description }}</td>
                        <td>
                            <a href="{{ route('admin.sub-lessons.edit', $subLesson->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>

                            <a href="{{ route('admin.sub-lesson-contents', $subLesson->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Lihat Konten</a>

                            <form action="{{ route('admin.sub-lessons.destroy', $subLesson->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this sub lesson?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
