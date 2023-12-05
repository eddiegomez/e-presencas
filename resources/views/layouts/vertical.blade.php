<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />

  <title>e-Presencas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
  <meta content="Coderthemes" name="author" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  @if (isset($isDark) && $isDark)
    @include("layouts.shared.head", ["isDark" => true])
  @elseif(isset($isRTL) && $isRTL)
    @include("layouts.shared.head", ["isRTL" => true])
  @else
    @include("layouts.shared.head")
  @endif

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
</body>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Include jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>   --}}

</html>
