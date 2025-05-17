@extends('app')

@section('title', 'Study')

@section('content')
    <!-- Progress Bar -->
    <div class="bg-gray-200 h-2">
        <div class="bg-[#18AEB5] h-2 rounded-r-full" style="width: {{ ($currentPosition / $totalSubLessons) * 100 }}%"></div>
    </div>

    <!-- Study Content -->
    <div class="max-w-3xl mx-auto px-4 py-8 pb-24">
        @foreach ($contents as $content)
            @if ($content->content_type === 'video')
                <!-- Video Lesson -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold">{{ $subLesson->title }}: Video Lesson</h2>
                        <span class="text-sm font-medium text-gray-500">{{ $currentPosition }} of
                            {{ $totalSubLessons }}</span>
                    </div>

                    <!-- Video Player -->
                    <div class="relative aspect-video bg-black rounded-lg mb-4 overflow-hidden">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $content->youtube_video_id }}"
                            title="{{ $subLesson->title }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600">
                                @if ($content->duration)
                                    {{ floor($content->duration / 60) }}:{{ str_pad($content->duration % 60, 2, '0', STR_PAD_LEFT) }}
                                    min
                                @endif
                            </span>
                        </div>
                        <div>
                            <button class="text-sm text-[#18AEB5] hover:text-[#006B87] font-medium">
                                <i class="fas fa-download mr-1"></i>
                                Download transcript
                            </button>
                        </div>
                    </div>
                </div>
            @elseif($content->content_type === 'text')
                <!-- Video Summary -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h3 class="font-bold mb-3">Video Summary</h3>
                    <div class="text-gray-700 mb-4 prose">
                        {!! nl2br(e($content->content)) !!}
                    </div>
                </div>
            @elseif($content->content_type === 'tip')
                <!-- Tip -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700">{{ $content->content }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
        <div class="max-w-3xl mx-auto flex justify-between">
            @if ($prevSubLesson)
                <form action="{{ route('lessons.sub.start', $prevSubLesson) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </button>
                </form>
            @else
                <a href="{{ route('lessons.sub', ['lesson_id' => $lesson->id]) }}"
                    class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Lesson
                </a>
            @endif

            <form action="{{ route('lessons.sub.study.complete', $subLesson) }}" method="POST">
                @csrf
                <button type="submit" class="bg-[#18AEB5] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#006B87]">
                    @if ($nextSubLesson)
                        Continue
                    @else
                        Complete
                    @endif
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
