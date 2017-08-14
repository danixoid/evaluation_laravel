<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('interface.quests_task') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('meta')

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ trans('interface.home') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if(Auth::check() && Auth::user()->hasAnyRole(['admin','manager']))
                            <li{!! preg_match("/exam/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('exam.index') }}">{!! trans('interface.exams') !!}</a></li>
{{--                            <li><a href="{{ route('ticket.index') }}">{!! trans('interface.tickets') !!}</a></li>--}}
                            <li{!! preg_match("/(org)|(position)|(user)/",request()->url()) ? " class=\"active\"" : " class=\"dropdown\"" !!}>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ trans('interface.references') }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li{!! preg_match("/quest/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('quest.index') }}">{!! trans('interface.quests_task') !!}</a></li>
                                    <li{!! preg_match("/org/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('org.index') }}">{!! trans('interface.orgs') !!}</a></li>
                                    <li{!! preg_match("/position/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('position.index') }}">{!! trans('interface.positions') !!}</a></li>
                                    <li{!! preg_match("/func/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('func.index') }}">{!! trans('interface.funcs') !!}</a></li>
                                    <li{!! preg_match("/user/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('user.index') }}">{!! trans('interface.users') !!}</a></li>
                                </ul>
                            </li>
                            <li{!! preg_match("/help/",request()->path()) ? " class=\"active\"" : "" !!}><a href="{{ route('help') }}">{!! trans('interface.help') !!}</a></li>
                            {{--                            <li><a href="{{ route('ticket.index') }}">{!! trans('interface.tickets') !!}</a></li>--}}

                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">

                        @foreach(config()->get('app.locales') as $lang => $name)
                            @if($lang != config()->get('app.locale'))
                                <li><a href="{{ route('lang',['lang' => $lang]) }}">{!! $name !!}</a></li>
                            @endif
                        @endforeach
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">{!! trans('interface.login') !!}</a></li>
                            <li><a href="{{ route('register') }}">{!! trans('interface.register') !!}</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {!! trans('interface.logout') !!}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container">
            <div class="row">
                <div class="col-md-offset-1 col-md-10">

                    @if(session()->has('warning'))
                        <div class="alert alert-warning alert-dismissible fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h3>{{ session('warning') }}</h3>
                        </div>
                    @endif

                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h3>{{ session('message') }}</h3>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Внимание!</strong> Обнаружены ошибки при заполнении полей.<br><br>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <!--        -->
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('javascript')
</body>
</html>
