@props([
    'userName' => '',
    'roleName' => '',
    'roleIcon' => 'fas fa-user',
    'roleColor' => 'primary',
    'description' => 'Selamat datang di Terra Assessment'
])

<div class="unified-card mb-6">
    <div class="unified-card-body">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-secondary-900">
                    Selamat datang, {{ $userName }}!
                </h2>
                <p class="text-secondary-600 mt-1">
                    {{ $roleName }} - {{ now()->format('l, d F Y') }}
                </p>
                @if($description)
                    <p class="text-sm text-secondary-500 mt-2">{{ $description }}</p>
                @endif
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 bg-gradient-to-br from-{{ $roleColor }}-500 to-{{ $roleColor }}-600 rounded-full flex items-center justify-center">
                    <i class="{{ $roleIcon }} text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
