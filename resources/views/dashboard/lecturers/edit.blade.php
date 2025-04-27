<x-dashboard-layout title="Edit Data Dosen">
    <x-slot name="header">
        Edit Data Dosen
    </x-slot>

    <div class="card p-4">
        <form method="POST" action="{{ route('dashboard.lecturers.update', $dosen) }}">
            @csrf
            @method('PUT')

            {{-- Full Name --}}
            <div class="mb-3">
                <label class="form-label" for="fullname">Nama Lengkap <span style="font-size:14px;color:red">*</span></label>
                <input type="text"
                       class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                       id="fullname"
                       name="fullname"
                       placeholder="Nama Lengkap"
                       value="{{ old('fullname', $dosen->fullname) }}"
                       required />
                <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <label class="form-label" for="department_id">Jurusan <span style="font-size:14px;color:red">*</span></label>
                <select class="form-select {{ $errors->get('department_id') ? 'border-danger' : '' }}"
                        id="department_id"
                        name="department_id"
                        required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $dosen->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
            </div>

            {{-- Functional Position --}}
            <div class="mb-3">
                <label class="form-label" for="functional_position">Jabatan Fungsional</label>
                <select class="form-select {{ $errors->get('functional_position') ? 'border-danger' : '' }}"
                        id="functional_position"
                        name="functional_position">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach (App\Enums\FunctionalPosition::cases() as $position)
                        <option value="{{ $position->value }}" {{ old('functional_position', $dosen->functional_position?->value) === $position->value ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $position->name)) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('functional_position')" />
            </div>

            {{-- Academic Rank --}}
            <div class="mb-3">
                <label class="form-label" for="academic_rank">Pangkat</label>
                <select class="form-select {{ $errors->get('academic_rank') ? 'border-danger' : '' }}"
                        id="academic_rank"
                        name="academic_rank">
                    <option value="">-- Pilih Pangkat --</option>
                    @foreach (App\Enums\AcademicRank::cases() as $rank)
                        <option value="{{ $rank->value }}" {{ old('academic_rank', $dosen->academic_rank?->value) === $rank->value ? 'selected' : '' }}>
                            {{ $rank->value }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('academic_rank')" />
            </div>

            {{-- Employee Status --}}
            <div class="mb-3">
                <label class="form-label" for="employee_status">Status Pegawai</label>
                <select class="form-select {{ $errors->get('employee_status') ? 'border-danger' : '' }}"
                    id="employee_status" name="employee_status">
                    <option value="">-- Pilih Status Pegawai --</option>
                    <option value="PNS" {{ old('employee_status', $dosen->employee_status) === 'PNS' ? 'selected' : '' }}>
                        PNS
                    </option>
                    <option value="Non PNS" {{ old('employee_status', $dosen->employee_status) === 'Non PNS' ? 'selected' : '' }}>
                        Non PNS
                    </option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('employee_status')" />
            </div>

            {{-- Certification Status --}}
            <div class="mb-3">
                <label class="form-label" for="certification_status">Status Sertifikasi</label>
                <select class="form-select {{ $errors->get('certification_status') ? 'border-danger' : '' }}"
                        id="certification_status"
                        name="certification_status">
                    <option value="">-- Pilih Status Sertifikasi --</option>
                    @foreach (App\Enums\CertificationStatus::cases() as $certification)
                        <option value="{{ $certification->value }}" {{ old('certification_status', $dosen->certification_status?->value) === $certification->value ? 'selected' : '' }}>
                            {{ $certification->value }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('certification_status')" />
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit" class="btn btn-primary">Update Data</button>
                <a href="{{ route('dashboard.lecturers.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</x-dashboard-layout>
