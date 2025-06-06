<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />

  <title>e-Presencas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Plataforma de Gestao de Eventos do INAGE" name="description" />
  <meta content="Coderthemes" name="author" />


  @if (isset($isDark) && $isDark)
    @include("layouts.shared.head", ["isDark" => true])
  @elseif(isset($isRTL) && $isRTL)
    @include("layouts.shared.head", ["isRTL" => true])
  @else
    @include("layouts.shared.head")
  @endif

  <!-- Include jQuery (required by Select2) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Include Select2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>

@if (isset($isScrollable) && $isScrollable)

  <body class="scrollable-layout">
  @elseif(isset($isBoxed) && $isBoxed)

    <body class="left-side-menu-condensed boxed-layout" data-left-keep-condensed="true">
    @elseif(isset($isDarkSidebar) && $isDarkSidebar)

      <body class="left-side-menu-dark">
      @elseif(isset($isCondensedSidebar) && $isCondensedSidebar)

        <body class="left-side-menu-condensed" data-left-keep-condensed="true">
        @else

          <body>
@endif

@if (isset($withLoader) && $withLoader)
  <!-- Pre-loader -->
  <div id="preloader">
    <div id="status">
      <div class="spinner">
        <div class="circle1"></div>
        <div class="circle2"></div>
        <div class="circle3"></div>
      </div>
    </div>
  </div>
  <!-- End Preloader-->
@endif

<div id="wrapper">

  @include("layouts.shared.header")
  @include("layouts.shared.sidebar")

  <div class="content-page">
    <div class="content">
      <!-- Start Content-->
      <div class="container-fluid">
        @yield("breadcrumb")
        @yield("content")
      </div>
    </div>

    @include("layouts.shared.footer")
  </div>
</div>

@include("layouts.shared.rightbar")

@include("layouts.shared.footer-script")

@if (getenv("APP_ENV") === "local")
@endif

<script src="{{ asset("assets/libs/select2/select2.min.js") }}"></script>


</body>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Include jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>   --}}

</html>
