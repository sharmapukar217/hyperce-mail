@extends('layouts.base')

@section('htmlBody')
    <div class="container-fluid">
        <div class="row">

            <div class="sidebar bg-purple-100 min-vh-100 d-none d-xl-block">

                <div class="mt-4">
                    <div class="logo text-center">
                        <a href="{{ route('dashboard') }}" style="display: contents;">
                            <img src="{{ asset('/img/logo-main.png') }}" alt="" width="175px" style="margin: -80px 0 -100px 0;">
                        </a>
                    </div>
                </div>

                <div class="mt-5">
                    @include('layouts.partials.sidebar')
                </div>
            </div>

            @include('layouts.main')
        </div>
    </div>
@endsection