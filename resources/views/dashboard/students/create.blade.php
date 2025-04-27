<x-dashboard-layout title="Tambah Data Mahasiswa">
    <x-slot name="header">
        Tambah Data Mahasiswa
    </x-slot>

    <div class="card p-4">
        <form method="POST" action="{{ route('dashboard.students.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label for="fullname" class="form-label">Nama Lengkap <span style="color:red">*</span></label>
                <input type="text"
                       class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                       id="fullname"
                       name="fullname"
                       placeholder="Nama Lengkap"
                       value="{{ old('fullname') }}"
                       autocomplete="name"
                       autofocus
                       required />
                <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
            </div>

            {{-- NIM --}}
            <div class="mb-3">
                <label for="nim" class="form-label">NIM <span style="color:red">*</span></label>
                <input type="text"
                       class="form-control {{ $errors->get('nim') ? 'border-danger' : '' }}"
                       id="nim"
                       name="nim"
                       placeholder="NIM"
                       value="{{ old('nim') }}"
                       autocomplete="off"
                       required />
                <x-input-error class="mt-2" :messages="$errors->get('nim')" />
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email <span style="color:red">*</span></label>
                <input type="email"
                       class="form-control {{ $errors->get('email') ? 'border-danger' : '' }}"
                       id="email"
                       name="email"
                       placeholder="Email"
                       value="{{ old('email') }}"
                       autocomplete="email"
                       required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            {{-- Angkatan --}}
            <div class="mb-3">
                <label for="batch" class="form-label">Angkatan <span style="color:red">*</span></label>
                <input type="number"
                       class="form-control {{ $errors->get('batch') ? 'border-danger' : '' }}"
                       id="batch"
                       name="batch"
                       placeholder="Angkatan"
                       value="{{ old('batch') }}"
                       autocomplete="off"
                       required />
                <x-input-error class="mt-2" :messages="$errors->get('batch')" />
            </div>

            {{-- Konsentrasi --}}
            <div class="mb-3">
                <label for="department_id" class="form-label">Program Studi <span style="color:red">*</span></label>
                <select class="form-select {{ $errors->get('department_id') ? 'border-danger' : '' }}"
                        id="department_id"
                        name="department_id"
                        required>
                    <option value="">Pilih Program Studi</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
            </div>

            {{-- Nomor HP --}}
            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="text"
                       class="form-control {{ $errors->get('phone_number') ? 'border-danger' : '' }}"
                       id="phone_number"
                       name="phone_number"
                       placeholder="Nomor HP"
                       value="{{ old('phone_number') }}"
                       autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control {{ $errors->get('address') ? 'border-danger' : '' }}"
                          id="address"
                          name="address"
                          placeholder="Alamat"
                          rows="2"
                          autocomplete="street-address">{{ old('address') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="photo" class="form-label">Foto</label>
                <img class="img-preview img-thumbnail rounded mb-2" style="width: 300px; height: auto;">
                <input type="file"
                       class="form-control {{ $errors->get('photo') ? 'border-danger' : '' }}"
                       id="photo"
                       name="photo"
                       accept=".png, .jpg, .jpeg" />
                <div class="form-text">
                    <small>PNG, JPG, atau JPEG (Max. 2 MB).</small>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>

            {{-- Submit --}}
            <div class="d-flex">
                <button type="submit" class="btn btn-primary">Tambah Data</button>
                <a href="{{ route('dashboard.students.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>

        </form>
    </div>
</x-dashboard-layout>
