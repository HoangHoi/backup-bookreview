<nav class="navbar navbar-inverse fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand hvr-wobble-bottom brand-header" href="{{ route('home') }}" style="color: orange;">
                {{ trans('label.brand') }}
            </a>
            <a class="navbar-brand hvr-wobble-bottom brand-header" href="{{ route('book.index') }}" style="color: orange;">
                {{ trans('label.library') }}
            </a>
        </div>
        {{ Form::open(['route' => 'book.search', 'method' => 'get', 'class' => 'navbar-left navbar-form']) }}
            <div style="float: left; margin-right: 2em;">
                {{ Form::text('query', null, ['id' => 'input-autocomplete', 'placeholder' => 'Search Book', 'data-url' => route('bookAutocomplete')]) }}
            </div>
            {{ Form::submit('Find', ['class' => 'btn btn-info']) }}
        {{ Form::close() }}
        @if (Auth::check())
            <ul class="nav navbar-nav navbar-right user-header">
                <li>
                    <div class="dropdown-toggle dropdown-user" data-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar_link }}" alt="avt-img">
                        {{ Auth::user()->name }}
                        <span class="caret"></span>
                    </div>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('users.show', Auth::user()->id) }}">{{ trans('user.timeline') }}</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('signout') }}">{{ trans('user.actions.logout') }}</a></li>
                    </ul>
                </li>
            </ul>
        @else
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ route('getSignup') }}">
                        <span class="glyphicon glyphicon-user"></span>
                        {{ trans('user.actions.register') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('getSignin') }}">
                        <span class="glyphicon glyphicon-log-in"></span>
                        {{ trans('user.actions.login') }}
                    </a>
                </li>
            </ul>
        @endif
    </div>
</nav>
