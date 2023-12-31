<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    @include('layouts.partials.og')
    @include('layouts.partials.favicons')

    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        {{ config('app.name') }}
    </title>

    <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @vite(['resources/sass/app.scss' ])
    @stack('css')

</head>
<body>

@yield('htmlBody')

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script>
    $('.sidebar-toggle').click(function (e) {
        e.preventDefault();
        toggleElements();
    });

    function toggleElements() {
        $('.sidebar').toggleClass('d-none');
    }
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-J4QTBGPBK6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-J4QTBGPBK6');
</script>

@stack('js')

</body>
</html>
