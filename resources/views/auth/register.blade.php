@php
    $baseUrl = config('app.url');

    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp
<x-auth-layout title="Register">
    <!-- Register -->
    <section class="d-flex justify-content-center align-items-center" style="width: 100%;">
        <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center fs-2 mb-3" style="display:flex;flex-direction:column;">
                    <img src="{{ $baseUrl }}/assets/images/logo-unima.png" class="img-fluid" style="width: 100px" />
                    <a href="{{ route('home') }}" class="app-brand-link mt-3">
                        E-Survey Unima
                    </a>
                </div>
                <h4 class="mb-3 fs-4 text-center">Selamat Datang di E-Survey Unima</h4>

                <form class="mb-3 mt-1 row" method="post" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="fullname">Nama Lengkap <span
                                style="font-size:14px;color:red">*</span></label>
                        <input type="text" class="form-control {{ $errors->get('fullname') ? 'border-danger' : '' }}"
                            id="fullname" name="fullname" placeholder="Nama Lengkap" value="{{ old('fullname') }}"
                            autocomplete="name" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('fullname')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="nim">NIM <span style="font-size:14px;color:red">*</span></label>
                        <input type="number" class="form-control {{ $errors->get('nim') ? 'border-danger' : '' }}"
                            id="nim" name="nim" placeholder="NIM" value="{{ old('nim') }}" autocomplete="nim"
                            required />
                        <x-input-error class="mt-2" :messages="$errors->get('nim')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="email">Email <span style="font-size:14px;color:red">*</span></label>
                        <input type="email" class="form-control {{ $errors->get('email') ? 'border-danger' : '' }}"
                            id="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email"
                            required />
                        <div id="form-email-help" class="form-text"></div>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="angkatan">Angkatan <span
                                style="font-size:14px;color:red">*</span></label>
                        <input type="number" class="form-control {{ $errors->get('angkatan') ? 'border-danger' : '' }}"
                            id="angkatan" min="2018" max="{{ date('Y') }}" name="angkatan" placeholder="Angkatan" value="{{ old('angkatan') }}"
                            autocomplete="year" required />
                        <x-input-error class="mt-2" :messages="$errors->get('angkatan')" />
                    </div>
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
                    <div class="mb-3 col-md-6 form-password-toggle">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                            placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6 form-password-toggle">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
                    </div>
                </form>
                <p class="text-center">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}">
                        <span>Masuk sekarang!</span>
                    </a>
                </p>
            </div>
        </div>
    </section>
</x-auth-layout>
