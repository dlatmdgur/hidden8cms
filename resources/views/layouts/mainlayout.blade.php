<!DOCTYPE html>
<html lang="ko">
  <head>
    @include('layouts.partials.head')
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
@include('layouts.partials.nav')

@include('layouts.partials.header')

@yield('content')

@include('layouts.partials.footer')
      </div>
    </div>

@include('layouts.partials.footer-scripts')

 </body>
</html>
