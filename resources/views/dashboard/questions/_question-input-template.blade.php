<div class="question-item mb-4 border p-3 rounded" data-index="__INDEX__" data-id="__ID__">
    <div class="mb-3">
        <label for="questions[__INDEX__][question_text]" class="form-label label-question">
            Pertanyaan __INDEX__ <span style="font-size:14px;color:red">*</span>
        </label>
        <input type="text" name="questions[__INDEX__][question_text]"
               class="form-control"
               id="questions[__INDEX__][question_text]"
               placeholder="Tuliskan pertanyaan..."
               required />
    </div>

    <div class="mb-3">
        <label for="questions[__INDEX__][scale]" class="form-label label-scale">
            Skala Penilaian <span style="font-size:14px;color:red">*</span>
        </label>
        <select name="questions[__INDEX__][scale]"
                id="questions[__INDEX__][scale]"
                class="form-select" required>
            <option value="1-5">1 - 5</option>
            <option value="1-3">1 - 3</option>
        </select>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-danger btn-sm remove-question">Hapus</button>
    </div>
</div>
