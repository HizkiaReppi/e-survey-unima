<x-dashboard-layout title="Daftar Pertanyaan Kategori: {{ $category->name }}">
    <x-slot name="header">
        Pertanyaan dalam Kategori: <strong>{{ $category->name }}</strong>
    </x-slot>

    <div class="card p-4">
        @if($category->questions->isEmpty())
            <p class="text-muted">Belum ada pertanyaan pada kategori ini.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Pertanyaan</th>
                            <th style="width: 150px;">Skala</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>{{ $question->scale }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('dashboard.category.index') }}" class="btn btn-outline-secondary">
                ← Kembali ke Daftar Kategori
            </a>
            <a href="{{ route('dashboard.category.questions.create-or-edit', $category) }}" class="btn btn-primary">
                + Kelola Pertanyaan
            </a>
        </div>
    </div>
</x-dashboard-layout>
