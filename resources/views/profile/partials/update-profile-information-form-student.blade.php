@php
    $concentrations = ['rpl', 'multimedia', 'tkj'];
@endphp

<section class="card mb-4">
    <h5 class="card-header pb-0">Profile Details</h5>
    <!-- Account -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update.student') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="fullname" class="form-label">Nama Lengkap</label>
                    <input class="form-control" type="text" id="fullname" name="fullname"
                        value="{{ old('fullname', $user->fullname) }}" placeholder="Nama Lengkap" autofocus required />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="nim" class="form-label">NIM</label>
                    <p class="border p-2 rounded m-0">{{ $user->formattedNIM }}</p>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-control" type="email" id="email" name="email"
                        value="{{ old('email', $user->user->email) }}" placeholder="Email" required />

                    @if ($user->user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->user->hasVerifiedEmail())
                        <div>
                            <p class="my-2" style="font-size:12px;">
                                Email anda belum terverifikasi.
                            </p>
                            <button form="send-verification"
                                class="btn btn-secondary" style="font-size:12px;">
                                Klik disini untuk mengirim ulang email verifikasi.
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1 fw-medium text-success" style="font-size:14px">
                                    Link verifikasi baru telah dikirim ke alamat email anda.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="phone_number">Nomor HP</label>
                    <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="Nomor HP" value="{{ old('phone_number', $user->phone_number) }}" />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <p class="border p-2 rounded m-0">{{ $user->batch }}</p>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control" name="address" id="address" placeholder="Alamat"
                        value="{{ old('address', $user->address) }}" rows="2">{{ old('address', $user->address) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto</label>
                    @if ($user->user->photo)
                        <img src="/{{ $user->user->photoFile }}" alt="{{ $user->fullname }}"
                            class="img-preview img-thumbnail rounded mb-2" style="width: 300px; height: auto;">
                    @else
                        <img class="img-preview img-thumbnail rounded" style="width: 300px; height: auto;">
                    @endif
                    <input class="form-control" type="file" id="photo" name="photo"
                        accept=".png, .jpg, .jpeg" />
                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                    <div id="form-help" class="form-text">
                        <small>PNG, JPG atau JPEG (Max. 2 MB).</small>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Simpan Data</button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>
    </form>
    <!-- /Account -->
</section>
