@extends("layouts.non-auth")



@section("content")
  <div class="alert alert-success text-center">
    A sua presenca foi confirmada para o evento {{ $event->name }}

  </div>

  <div class="row mt-4">
    <div class="col-12 col-md-4 mx-auto">
      <div href="/event/{{ $event->id }}" class="card position-relative">
        <img src="{{ asset("storage/" . $event->banner_url) }}" class="card-img-top" alt="Event Banner" />
        <div class="card-body z-10">
          <h2 class="card-text text-center text-capitalize">{{ $event->name }}</h2>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Data</span>
            <span class="font-weight-medium font-size-16">{{ $event->start_date }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Localizacao</span>
            <span class="font-weight-medium font-size-16">{{ $event->addresses[0]->name }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Hora de Inicio</span>
            <span class="font-weight-medium font-size-16">{{ $event->start_time }}</span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <span class="font-weight-bolder font-size-16">Hora de fim</span>
            <span class="font-weight-medium font-size-16">{{ $event->end_time }}</span>
          </div>

          @if ($event->schedules->count() == 0)
            <span class="font-size-14 btn btn-danger w-100 mt-2 disabled">Ainda nao temos o programa deste evento!</span>
          @endif

          @foreach ($event->schedules as $schedule)
            <a href="{{ asset("storage/schedules/" . $schedule->pdf_url) }}" target="_blank"
              class="btn btn-secondary mt-2 w-100">Programa
              do dia {{ $schedule->date }}
            </a>
          @endforeach

          {{-- <a href="#" class="btn btn-primary mt-2 w-100">Verificar o Programa</a> --}}
        </div>
      </div>
    </div>
  </div>
@endsection
