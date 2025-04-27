<x-dashboard-layout title="Terima Kasih">
    <x-slot name="header">Terima Kasih!</x-slot>

    <div class="card alert alert-success">
        Terima kasih telah mengisi survey. Jawaban Anda sangat berharga untuk peningkatan mutu pendidikan!
    </div>

    <a href="{{ route('dashboard.survey.result.show', $id) }}" class="btn btn-primary">Lihat Jawaban</a>
</x-dashboard-layout>
