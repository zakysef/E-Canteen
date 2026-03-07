<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — E-Canteen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    @stack('head')
</head>
<body class="h-full font-sans antialiased">

{{-- Flash messages --}}
@if(session('success') || session('error') || $errors->any())
<div class="fixed top-5 right-5 z-[999] flex flex-col gap-2 max-w-sm w-full"
     x-data="{ items: [
        @if(session('success')) { type:'success', msg: {{ json_encode(session('success')) }} }, @endif
        @if(session('error'))   { type:'error',   msg: {{ json_encode(session('error')) }} },   @endif
        @if($errors->any())     { type:'error',   msg: {{ json_encode($errors->first()) }} },    @endif
     ]}"
>
    <template x-for="(item, i) in items" :key="i">
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 translate-x-4"
             :class="item.type === 'success' ? 'border-green-200 shadow-green-100/50' : 'border-red-200 shadow-red-100/50'"
             class="flex items-start gap-3 bg-white border rounded-2xl px-4 py-3.5 shadow-xl">
            <i :class="item.type === 'success' ? 'ph-check-circle text-green-500' : 'ph-x-circle text-red-500'"
               class="ph text-xl flex-shrink-0 mt-0.5"></i>
            <p class="text-sm font-medium text-gray-800 flex-1" x-text="item.msg"></p>
            <button @click="show = false">
                <i class="ph ph-x text-base text-gray-400 hover:text-gray-600"></i>
            </button>
        </div>
    </template>
</div>
@endif

@yield('content')
@stack('scripts')
</body>
</html>
