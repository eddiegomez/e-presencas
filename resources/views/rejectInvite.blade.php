@extends("layouts.non-auth")

@section("content")
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

  <div class="row mt-4">
    <div class="col-12 col-md-4 mx-auto">
      <div href="/event/{{ $invite->event->id }}" class="card position-relative">
        <img src="{{ asset("storage/" . $event->banner_url) }}" class="card-img-top" alt="Event Banner" />
        <div class="card-body z-10">
          <h2 class="card-text text-center text-capitalize">{{ $invite->event->name }}</h2>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Data</span>
            <span class="font-weight-medium font-size-16">{{ $invite->event->start_date }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Localizacao</span>
            <span class="font-weight-medium font-size-16">{{ $invite->event->addresses[0]->name }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Hora de Inicio</span>
            <span class="font-weight-medium font-size-16">{{ $invite->event->start_time }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Hora de fim</span>
            <span class="font-weight-medium font-size-16">{{ $invite->event->end_time }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
