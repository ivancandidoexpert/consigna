@extends('layouts.app')

@section('content')

        <!-- EXIBINDO STATUS MESSAGES -->
        @if (Session::has('notification_success'))
            <div class="alert alert-success">{{ Session::get('notification_success')}}</div>
        @endif
        @if (Session::has('notification_danger'))
            <div class="alert alert-danger">{{ Session::get('notification_danger')}}</div>
        @endif
        @if (Session::has('notification_warning'))
            <div class="alert alert-warning">{{ Session::get('notification_warning')}}</div>
        @endif

        <!-- EXIBINDO ERROS -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <UL>
                @foreach ($errors->any() as $error)
                    <LI>{{$error}}</LI>
                @endforeach
                </UL>
            </div>
        @endif

        <!--- LOGIN AIND NÃO SEI O QUE FAZER COM ISSO -->
        @if (Route::has('login'))
            <div class="top-right links">
                @if (Auth::check())

        <!-- BODY -->
        <h3 class="text-center">@yield('title')</h3>
        <div class="container">
            @yield('body')
        </div>


                @else
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                @endif
            </div>
        @endif
@stop
