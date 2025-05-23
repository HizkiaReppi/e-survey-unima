<x-dashboard-layout title="Survey Kepuasan Mahasiswa">
    <x-slot name="header">Form Survey Kepuasan</x-slot>

    <div class="card p-4 mb-4">
        <h1>Halo {{ auth()->user()->name }}!</h1>
    </div>

    <div class="card alert alert-info">
        <p>
            Silakan isi semua pertanyaan dengan jujur. Anda hanya dapat mengisi survey <strong>satu kali saja</strong>.
        </p>

        <p style="margin-bottom: 0">Petunjuk:</p>
        <ol type="a">
            <li>Angket ini menunjukkan tanggapan Anda terhadap pembelajaran yang dilaksanakan oleh dosen yang berguna
                untuk perbaikan mutu pembelajaran.</li>
            <li>Jawaban yang Anda berikan dijamin kerahasiaannya, dan tidak berpengaruh terhadap nilai mata kuliah atau
                status Anda sebagai mahasiswa. Oleh karena itu, Anda diminta untuk memberikan penilaian secara
                sungguh-sungguh.</li>
            <li>Kriteria bobot penilaian adalah sebagai berikut:
                <ul>
                    <li>5 = Sangat Baik (81% - 100%)</li>
                    <li>4 = Baik (61% - 80%)</li>
                    <li>3 = Cukup Baik (41% - 60%)</li>
                    <li>2 = Kurang (21% - 40%)</li>
                    <li>1 = Sangat Kurang (1% - 20%)</li>
                </ul>
            </li>
        </ol>
    </div>

    <form method="POST" action="{{ route('dashboard.survey.store', $course->id) }}" id="surveyForm">
        @csrf

        <div class="card p-4 mb-4">
            <h5>Informasi Perkuliahan {{ $course->name }}</h5>
            <hr class="mb-3" style="margin-top: 0px;">

            <div class="mb-3">
                <label class="form-label">Pilih Dosen <span class="text-danger">*</span></label>
                <select name="lecturer_id" id="lecturerSelect" class="form-select" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($lecturers as $lecturer)
                        <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                            {{ $lecturer->fullname }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('lecturer_id')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
                <input type="number" name="credits" id="creditsInput" class="form-control" min="1" max="6" placeholder="Jumlah SKS" value="{{ old('credits') }}"
                 required>
                <x-input-error :messages="$errors->get('credits')" class="mt-2" />
            </div>
        </div>

        <div id="questionIndicator" class="mb-3 text-muted"></div>


        @foreach ($categories as $index => $category)
            <div class="card p-4 survey-category d-none" data-index="{{ $index }}">
                <h5>{{ $category->name }}</h5>
                <hr class="mb-3" style="margin-top: 0px;">

                @foreach ($category->questions as $question)
                    <div class="mb-3">
                        <label class="form-label fs-6 d-block label-question">{{ $loop->iteration }}.
                            {{ $question->question_text }}</label>
                        @php
                            $scale = $question->scale === '1-3' ? range(1, 3) : range(1, 5);
                        @endphp
                        @foreach ($scale as $value)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="responses[{{ $question->id }}]"
                                    id="q{{ $question->id }}-{{ $value }}" value="{{ $value }}"
                                    required>
                                <label class="form-check-label"
                                    for="q{{ $question->id }}-{{ $value }}">{{ $value }}</label>
                            </div>
                        @endforeach
                        <x-input-error :messages="$errors->get('responses.' . $question->id)" class="mt-2" />
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-secondary" id="btnBack" disabled>Kembali</button>
            <button type="button" class="btn btn-primary" id="btnNext">Lanjut</button>
        </div>
    </form>
</x-dashboard-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {        
        $('#lecturerSelect').select2({
            theme: 'bootstrap-5',
            placeholder: "Pilih Dosen",
            allowClear: true
        });
    });
</script>

<script>
    const questions = document.querySelectorAll('.label-question');
    const categories = document.querySelectorAll('.survey-category');
    const btnNext = document.getElementById('btnNext');
    const btnBack = document.getElementById('btnBack');
    const questionIndicator = document.getElementById('questionIndicator');

    let currentIndex = 0;
    const total = categories.length;

    function updateQuestionIndicator() {
        const totalQuestions = [...categories].reduce((acc, category) => acc + category.querySelectorAll(
            'input[type="radio"]').length, 0);
        const answeredQuestions = [...categories].reduce((acc, category) => acc + category.querySelectorAll(
            'input[type="radio"]:checked').length, 0);

        questionIndicator.textContent = `Pertanyaan yang dijawab: ${answeredQuestions} dari ${questions.length}`;
    }

    function showCategory(index) {
        categories.forEach((cat, i) => {
            cat.classList.toggle('d-none', i !== index);
        });

        btnBack.disabled = index === 0;
        btnNext.textContent = index === total - 1 ? 'Kirim Survey' : 'Lanjut';

        updateQuestionIndicator();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function validateCategory(index) {
        const currentCategory = categories[index];
        const radios = currentCategory.querySelectorAll('input[type=radio]');
        const questionIds = [...new Set([...radios].map(r => r.name))];

        return questionIds.every(name =>
            currentCategory.querySelector(`input[name="${name}"]:checked`)
        );
    }

    btnNext.addEventListener('click', () => {
        if (!validateCategory(currentIndex)) {
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi dulu!',
                text: 'Silakan isi semua pertanyaan sebelum lanjut.'
            });
            return;
        }

        if (currentIndex === total - 1) {
            Swal.fire({
                title: 'Kirim Survey?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('surveyForm').submit();
                    localStorage.clear();
                }
            });
        } else {
            currentIndex++;
            showCategory(currentIndex);
        }
    });

    btnBack.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showCategory(currentIndex);
        }
    });

    const allRadios = document.querySelectorAll('input[type=radio]');
    allRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            localStorage.setItem(radio.name, radio.value);
            updateQuestionIndicator();
        });

        const savedValue = localStorage.getItem(radio.name);
        if (savedValue === radio.value) {
            radio.checked = true;
        }
    });

    showCategory(currentIndex);
</script>
