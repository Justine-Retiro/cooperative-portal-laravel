<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body>
    @include('partials.topbar')
    <div class="" @auth id="wrapper" @endauth>
        @auth
            @include('partials.sidebar')
        @endauth

        @yield('content')
    </div>
@yield('script')
</body>
</html>