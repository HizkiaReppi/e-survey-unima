@php
    $baseUrl = config('app.url');

    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp
<x-auth-layout title="Lupa Password">
    <section class="d-flex justify-content-center align-items-center" style="width: 100%; height: 100vh;">
        <div class="card">
            <div class="card-body">
                <div class="app-brand justify-content-center fs-2 mb-3" style="display:flex;flex-direction:column;">
                    <img src="{{ $baseUrl }}/assets/images/logo-unima.png" class="img-fluid" style="width: 100px" />
                    <a href="{{ route('home') }}" class="app-brand-link mt-3">
                        E-Survey Unima
                    </a>
                </div>
                <h4 class="mb-2">Reset Password</h4>
                <p class="mb-3">
                    Silahkan masukkan email yang terdaftar dan password baru Anda.
                </p>
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Masukan Email Anda" value="{{ old('email', $request->email) }}" autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirmation" class="form-control"
                                name="password_confirmation" placeholder="******" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
                </form>
            </div>
        </div>
    </section>
</x-auth-layout>
