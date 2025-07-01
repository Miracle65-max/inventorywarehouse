@props(['user', 'size' => 'md', 'class' => ''])

@php
    $sizeClasses = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8', 
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
        '2xl' => 'w-20 h-20',
        '3xl' => 'w-24 h-24',
        'profile' => 'w-32 h-32'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if($user && $user->avatar && Storage::disk('public')->exists($user->avatar))
    <img src="{{ asset('storage/' . $user->avatar) }}" 
         alt="{{ $user->full_name ?? $user->name ?? 'User' }}'s avatar" 
         class="{{ $sizeClass }} rounded-full object-cover border-2 border-gray-200 {{ $class }}">
@else
    <div class="{{ $sizeClass }} rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center {{ $class }}">
        <span class="text-gray-500 font-medium text-sm">
            {{ $user ? strtoupper(substr($user->full_name ?? $user->name ?? 'U', 0, 1)) : '?' }}
        </span>
    </div>
@endif 