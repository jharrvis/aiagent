<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-white leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div
                class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-2xl border border-transparent dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-2xl border border-transparent dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div
                class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-2xl border border-transparent dark:border-slate-800">
                <div class="max-w-xl text-slate-900 dark:text-gray-100">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>