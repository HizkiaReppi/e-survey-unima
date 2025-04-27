<x-dashboard-layout title="Manajemen Mahasiswa">
    <x-slot name="header">
        Manajemen Kelompok Penjaminan Mutu
    </x-slot>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Daftar Kelompok Penjaminan Mutu</h5>
            <a href="{{ route('dashboard.quality-assurance.create') }}" class="btn btn-primary me-4">Tambah
                Pengguna</a>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-4">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Terakhir Dilihat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($qualityAssurances as $qualityAssurance)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $qualityAssurance->name }}</td>
                            <td class="text-center">{{ $qualityAssurance->username }}</td>
                            <td class="text-center">{{ $qualityAssurance->email }}</td>
                            <td class="text-center">
                                @if ($qualityAssurance->isOnline())
                                    <span class="badge text-bg-primary">Online</span>
                                @else
                                    <span
                                        class="badge text-bg-secondary">{{ $qualityAssurance->lastActivityAgo() }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.quality-assurance.show', $qualityAssurance->id) }}">
                                            <i class="bx bxs-user-detail me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.quality-assurance.edit', $qualityAssurance->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('dashboard.quality-assurance.destroy', $qualityAssurance->id) }}"
                                            data-confirm-delete="true">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
