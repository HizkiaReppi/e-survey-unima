@php
    $textError = $errors->get("questions.$index.question_text");
    $scaleError = $errors->get("questions.$index.scale");
@endphp


<div class="question-item mb-4 border p-3 rounded" data-index="{{ $index }}" data-id="{{ $question->id ?? '' }}">
    <div class="mb-3">
        <label for="questions[{{ $index }}][question_text]" class="form-label label-question">
            Pertanyaan {{ $index + 1 }} <span style="font-size:14px;color:red">*</span>
        </label>
        <input type="text" name="questions[{{ $index }}][question_text]"
            class="form-control {{ $textError ? 'border-danger' : '' }}"
            id="questions[{{ $index }}][question_text]"
            value="{{ old("questions.$index.question_text", $question->question_text ?? '') }}"
            placeholder="Tuliskan pertanyaan..." required />

        <x-input-error class="mt-2" :messages="$textError" />
    </div>

    <div class="mb-3">
        <label for="questions[{{ $index }}][scale]" class="form-label label-scale">
            Skala Penilaian <span style="font-size:14px;color:red">*</span>
        </label>
        <select name="questions[{{ $index }}][scale]"
                id="questions[{{ $index }}][scale]"
                class="form-select {{ $scaleError ? 'border-danger' : '' }}" required>
            <option value="1-5" {{ (old("questions.$index.scale", $question->scale ?? '') == '1-5') ? 'selected' : '' }}>1 - 5</option>
            <option value="1-3" {{ (old("questions.$index.scale", $question->scale ?? '') == '1-3') ? 'selected' : '' }}>1 - 3</option>
        </select>

        <x-input-error class="mt-2" :messages="$scaleError" />
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-danger btn-sm remove-question">Hapus</button>
    </div>
</div>
