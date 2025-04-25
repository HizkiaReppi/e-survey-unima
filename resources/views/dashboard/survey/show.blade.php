<x-dashboard-layout title="Hasil Survey">
    <x-slot name="header">Hasil Survey Kepuasan</x-slot>

    @foreach ($categories as $category)
        <div class="card mb-3">
            <div class="card-header">
                <h5>{{ $category->name }}</h5>
            </div>
            <div class="card-body">
                @foreach ($category->questions as $question)
                    <div class="mb-2">
                        <strong>{{ $loop->iteration }}. {{ $question->question_text }}</strong>
                        <ul>
                            @foreach ($question->responses as $response)
                                @if ($response->user_id == auth()->id())
                                    <li><strong>Jawaban:</strong> {{ $response->score }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</x-dashboard-layout>
