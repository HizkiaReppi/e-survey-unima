<x-dashboard-layout title="Hasil Survey">
    <x-slot name="header">Hasil Survey Kepuasan Mata Kuliah {{ $course->name }}</x-slot>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Detail Mata Kuliah</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="course-name" class="form-label">Nama Mata Kuliah</label>
                    <p class="border p-2 rounded m-0">{{ $course->name }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="lecturer-name" class="form-label">Nama Dosen Pengampu</label>
                    <p class="border p-2 rounded m-0">{{ $response->lecturer->fullname }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="semester" class="form-label">Tahun Akademik</label>
                    <p class="border p-2 rounded m-0">{{ $course->period->name }}</p>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="response-time" class="form-label">Waktu Pengisian</label>
                    <p class="border p-2 rounded m-0">{{ $response->created_at->format('d-m-Y H:i') }}
                        ({{ $response->created_at->diffForHumans() }})</p>
                </div>
            </div>
        </div>
    </div>

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
    <div class="card mb-3">
        <div class="card-body">
            @php
                $backUrl =
                    auth()->user()->role == 'admin'
                        ? route('dashboard.survey.results.index')
                        : route('dashboard.survey.index');
            @endphp
            <a href="{{ $backUrl }}" class="btn btn-primary ms-2">Kembali</a>
            <button id="scroll-to-top" class="btn btn-outline-secondary ms-2">Scroll ke Atas</button>
        </div>
    </div>

    <script>
        const scrollToTopButton = document.getElementById('scroll-to-top');
        scrollToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</x-dashboard-layout>
