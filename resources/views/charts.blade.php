@extends('layouts.vertical')


@section('css')

@endsection

@section('breadcrumb')
<div class="row page-title">
    <div class="col-md-12">
        <nav aria-label="breadcrumb" class="float-right mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Shreyu</a></li>
                <li class="breadcrumb-item"><a href="#">Components</a></li>
                <li class="breadcrumb-item active" aria-current="page">Charts</li>
            </ol>
        </nav>
        <h4 class="mb-1 mt-0">Charts</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Line with Data Labels</h4>

                <div id="apex-line-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Gradient Line Chart</h4>

                <div id="apex-line-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Stacked Area</h4>

                <div id="apex-area" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Basic Column Chart</h4>

                <div id="apex-column-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->


<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Column Chart with Datalabels</h4>

                <div id="apex-column-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Mixed Chart - Line & Area</h4>

                <div id="apex-mixed-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Basic Bar Chart</h4>

                <div id="apex-bar-1" class="apex-charts"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Bar with Negative Values</h4>

                <div id="apex-bar-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Line, Column & Area Chart</h4>

                <div id="apex-mixed-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Multiple Y-Axis Chart</h4>

                <div id="apex-mixed-3" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Simple Bubble Chart</h4>

                <div id="apex-bubble-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">3D Bubble Chart</h4>

                <div id="apex-bubble-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Scatter (XY) Chart</h4>

                <div id="apex-scatter-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Scatter Chart - Datetime</h4>

                <div id="apex-scatter-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-4">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mt-0 mb-3">Simple Pie Chart</h4>

                <div id="apex-pie-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Gradient Donut Chart</h4>

                <div id="apex-pie-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Patterned Donut Chart</h4>

                <div id="apex-pie-3" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->


<div class="row">
    <div class="col-xl-4">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Basic RadialBar Chart</h4>

                <div id="apex-radialbar-1" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mt-0 mb-3">Multiple RadialBars</h4>

                <div id="apex-radialbar-2" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-3">Stroked Circular Guage</h4>

                <div id="apex-radialbar-3" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row -->
@endsection

@section('script')
<script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
<script src="https://apexcharts.com/samples/assets/series1000.js"></script>
<script src="https://apexcharts.com/samples/assets/ohlc.js"></script>

<!-- third party:js -->
<script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<!-- third party end -->
@endsection

@section('script-bottom')
<!-- init js -->
<script src="{{ URL::asset('assets/js/pages/apexcharts.init.js') }}"></script>
@endsection