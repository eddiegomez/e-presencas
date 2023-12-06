@extends("layouts.vertical")


@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  @if (session("success"))
    <div class="alert alert-success text-center font-bold">
      {{ session("success") }}
    </div>
  @endif

  @if (session("warning"))
    <div class="alert alert-warning text-center font-bold">
      {{ session("warning") }}
    </div>
  @endif

  @if (session("error"))
    <div class="alert alert-warning text-center font-bold">
      {{ session("error") }}
    </div>
  @endif

  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb fs-1">
          <h4 class="breadcrumb-item text-muted fs-4"><a href="{{ route("organizations.list") }}"
              class="text-muted">Instituicao</a></h4>
          <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $organization->name }}</h4>
        </ol>
      </nav>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createManagerModal">
          <i class='uil uil-upload-alt mr-1'></i>Criar Instituição
        </button>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection
