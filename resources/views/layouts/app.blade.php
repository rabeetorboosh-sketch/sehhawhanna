@php use App\Models\Short; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .upNav {
            display: flex;
            gap: 10px; /* تباعد بين الروابط */
            background-color: #f9f9f9; /* خلفية فاتحة */
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            flex-wrap: wrap; /* لتفادي كسر التصميم على الشاشات الصغيرة */
        }

        .upNav a {
            text-decoration: none;
            color: #333; /* لون النص */
            background-color: #e0e0e0; /* خلفية خفيفة للروابط */
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .upNav a:hover {
            background-color: #4a5f77;; /* لون عند المرور بالفأرة */
            color: #fff;
            transform: translateY(-2px); /* تأثير رفع خفيف */
        }
    </style>
    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div>
                        {{ $header }}
                    </div>
                    <button onclick="window.history.back()"
                            class="bak-arrow">
                        <i class="fa fa-arrow-right"></i> عودة
                    </button>
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
