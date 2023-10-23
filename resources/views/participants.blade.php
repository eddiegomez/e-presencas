@extends('layouts.vertical')

@section('css')
  <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" 
@endsection

@section('breadcrumbs')

<div>
<div class="row page-title align-items-center">
  <div class="col-sm-4 col-xl-6">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb fs-1">
                <h4 class="breadcrumb-item text-muted fs-4"><a href="/events" class="text-muted">Participantes</a></h4>
            </ol>
        </nav>
    </div>
</div>
@endsection