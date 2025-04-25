<x-dashboard-layout title="Tambah Pertanyaan">
    <x-slot name="header">
        Tambah Pertanyaan pada Kategori: <strong>{{ $category->name }}</strong>
    </x-slot>

    <div class="card p-4">
        <form method="POST" action="{{ route('dashboard.category.questions.store', $category) }}">
            @csrf

            <input type="hidden" name="category_id" value="{{ $category->id }}">

            <div class="alert alert-info d-flex align-items-start" style="font-size: 15px">
                <div class="me-2"><i class="bx bx-info-circle"></i></div>
                <div>
                    <strong>Petunjuk:</strong> Anda dapat menambah, mengedit, atau menghapus pertanyaan dalam kategori
                    ini.<br>
                    Jangan lupa untuk menekan tombol <strong>"Simpan Perubahan Pertanyaan"</strong> agar perubahan
                    tersimpan.
                </div>
            </div>

            <div id="question-fields">
                @forelse ($questions as $index => $question)
                    @include('dashboard.questions._question-input', [
                        'index' => $index,
                        'question' => $question,
                    ])
                @empty
                    @include('dashboard.questions._question-input', ['index' => 0, 'question' => null])
                @endforelse
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary" id="add-question">Tambah Pertanyaan</button>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan Pertanyaan
                </button>
                <a href="{{ route('dashboard.category.index', $category) }}" class="btn btn-outline-secondary ms-2">
                    <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Kategori
                </a>
            </div>
        </form>
    </div>

    <template id="question-template">
        @include('dashboard.questions._question-input-template')
    </template>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Konfirmasi Hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr />
                <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">
                    Apakah Anda yakin ingin menghapusnya?
                </div>
                <hr />
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let questionIndex = {{ count($questions) > 0 ? count($questions) : 1 }};
        let questionToDelete = null;

        function updateQuestionIndexes() {
            const items = document.querySelectorAll('.question-item');
            items.forEach((item, idx) => {
                const newIndex = idx;
                item.dataset.index = newIndex;

                const questionInput = item.querySelector('input[name$="[question_text]"]');
                const scaleSelect = item.querySelector('select[name$="[scale]"]');

                questionInput.name = `questions[${newIndex}][question_text]`;
                questionInput.id = `questions[${newIndex}][question_text]`;

                scaleSelect.name = `questions[${newIndex}][scale]`;
                scaleSelect.id = `questions[${newIndex}][scale]`;

                item.querySelector('.label-question').textContent = `Pertanyaan ${newIndex + 1} *`;
                item.querySelector('.label-scale').textContent = `Skala Penilaian *`;
            });
        }

        document.getElementById('add-question').addEventListener('click', function() {
            questionIndex++;
            const container = document.getElementById('question-fields');
            const template = document.getElementById('question-template').innerHTML;
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = template.replace(/__INDEX__/g, questionIndex);
            container.appendChild(tempDiv.firstElementChild);
            updateQuestionIndexes();
        });

        document.getElementById('question-fields').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-question')) {
                const item = e.target.closest('.question-item');
                const isExisting = item.dataset.id !== "";

                if (isExisting) {
                    questionToDelete = item;
                    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                    modal.show();
                } else {
                    item.remove();
                    updateQuestionIndexes();
                }
            }
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (questionToDelete) {
                questionToDelete.remove();
                updateQuestionIndexes();
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                modal.hide();
            }
        });

        updateQuestionIndexes();
    </script>
</x-dashboard-layout>
