<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Photo Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Photo') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Update your profile photo.') }}
                            </p>
                        </header>

                        <div class="mt-6 flex items-center space-x-6">
                            <div class="shrink-0">
                                <img class="h-24 w-24 object-cover rounded-full border border-gray-200"
                                    src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}">
                            </div>
                            <div>
                                <form method="POST" action="{{ route('profile.photo.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <x-input-label for="profile_photo" :value="__('New Photo')" />
                                        <input id="profile_photo" name="profile_photo" type="file"
                                            class="mt-1 block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100"
                                            required>
                                        <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                                    </div>

                                    <div class="flex items-center gap-4 mt-4">
                                        <x-primary-button>{{ __('Save') }}</x-primary-button>

                                        @if (session('status') === 'profile-photo-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition
                                                x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                                {{ __('Saved.') }}</p>
                                        @endif
                                    </div>
                                </form>

                                @if (auth()->user()->profile_photo_path)
                                    <form method="POST" action="{{ route('profile.photo.remove') }}" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button
                                            onclick="return confirm('Are you sure you want to remove your profile photo?')">
                                            {{ __('Remove Photo') }}
                                        </x-danger-button>

                                        @if (session('status') === 'profile-photo-removed')
                                            <p x-data="{ show: true }" x-show="show" x-transition
                                                x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                                {{ __('Photo removed.') }}</p>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('profile_photo');
            const photoPreview = document.querySelector('.shrink-0 img');

            if (photoInput && photoPreview) {
                photoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        });
    </script>
</x-app-layout>
