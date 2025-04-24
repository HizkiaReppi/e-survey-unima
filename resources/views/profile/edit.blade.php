<x-dashboard-layout title="Profil Saya">
    <x-slot name="header">
        Profil Saya
    </x-slot>

    @if ($user->role == 'admin')
        @include('profile.partials.update-profile-information-form-admin')
    @elseif($user->user->role == 'student')
        @include('profile.partials.update-profile-information-form-student')
    @endif

    @include('profile.partials.update-password-form')

    @include('profile.partials.delete-user-form')

</x-dashboard-layout>
