<link rel="shortcut icon" href="{{ URL::asset("assets/images/favicon.ico") }}">

@yield("css")

<!-- App css -->
<link href="{{ URL::asset("assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset("assets/libs/select2/select2.min.css") }}" rel="stylesheet" type="text/css" />


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@if (isset($isDark) && $isDark)
  <link href="{{ URL::asset("assets/css/bootstrap-dark.min.css") }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset("assets/css/app-dark.min.css") }}" rel="stylesheet" type="text/css" />
@else
  <link href="{{ URL::asset("assets/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />

  @if (isset($isRTL) && $isRTL)
    <link href="{{ URL::asset("assets/css/app-rtl.min.css") }}" rel="stylesheet" type="text/css" />
  @else
    <link href="{{ URL::asset("assets/css/app.min.css") }}" rel="stylesheet" type="text/css" />
  @endif
@endif
