@php use App\Models\Short; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'monitoring') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>

        body{
            background: #f1eee6;
        }
        .upNav {
            display: flex;
            gap: 10px;
            background-color: #cccecc;
            padding: 8px 12px;
            /* border-radius: 8px; */
            /* box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); */
            /* margin-bottom: 15px; */
            flex-wrap: wrap;
        }

        .upNav a {
            text-decoration: none;

            background-color: #2a4462e6;
            padding: 5px 12px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 2px outset #cfd6de;
        }

        .upNav a:hover {
            background-color: #4a5f77;; /* لون عند المرور بالفأرة */
            color: #fff;
            transform: translateY(-2px); /* تأثير رفع خفيف */
        }
    </style>
    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="{{asset('manifest.json')}} ">
    <meta name="theme-color" content="#0d6efd">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>

</head>
<body class="font-sans antialiased">
<div class="min-h-screen  dark:bg-gray-900 flex">

    <!-- 🚀 الزر لفتح/إغلاق السايدبار -->
    <button id="toggleSidebar" class="toggle-btn">☰</button>

    <!-- 🚀 السايدبار -->
        @include('layouts.sidebar')

    <!-- 🚀 محتوى الصفحة -->
    <div class="flex-1">
        <ul id="navCustomMenu" class="custom-menu up">
            <li data-action="openNewTab">فتح في تبويب جديد</li>
            <li data-action="addShortcut">
                <form id="shortcutForm" action="{{ route('shorts.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="name" id="navShortcutName">
                    <input type="hidden" name="url" id="navShortcutUrl">
                    <input type="submit" value="حذف من الاختصارات ">
                </form>
            </li>
        </ul>
        @php
            $shorts= Short::where('user_id',\Illuminate\Support\Facades\Auth::id())->get();
        @endphp

        @if($shorts->isNotEmpty())
            <div class="upNav">
                @foreach($shorts as $short)
                    <a href="{{$short->url}}"> {{$short->name}}</a>
                @endforeach
            </div>
        @endif

        @isset($header)
            <header class="dark:bg-gray-800 shadow">
                <div class="  mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center" style=" background: #263f5b2e;
    box-shadow: -1px 2px 2px 0px #273f5b;">
                    <div  >
                        {{ $header }}
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('تسجيل الخروج  ') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        <button onclick="window.history.back()"
                                class="bak-arrow">
                            <i class="fa fa-arrow-right"></i> عودة
                        </button>
                    </div>

                </div>
            </header>
        @endisset

        <main>

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
