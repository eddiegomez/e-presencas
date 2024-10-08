@extends("layouts.vertical")


@section("css")
<link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("css/style.css") }}" rel="stylesheet">
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
    <h4 class="mb-1 mt-0">Eventos</h4>
  </div>
  <div class="col-sm-8 col-xl-6">
    <div class="float-sm-right mt-3 mt-sm-0">
      <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createEventModal">
        <i class='uil uil-upload-alt mr-1'></i>Criar Evento
      </button>
    </div>
  </div>
</div>

{{-- Divider line --}}
<div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection


@section("content")
<div class="row">
  @foreach ($events as $event)
  <div class="col-md-6 col-xl-3">
    <a class="card position-relative"
      onClick='EventDetailsModal({{ $event->id }} , "{{ $event->name }}", @json($event->start_date), @json($event->end_date), "{{ date("H:i", strtotime($event->start_time)) }}", "{{ date("H:i", strtotime($event->end_time)) }}", @json($event->banner_url), @json($event->addresses[0]->name), "{{ route("event.show", $event->id) }}")'
      style="cursor:pointer">
      <div class="card" style="width: 18rem;">
        @if($event->banner_url == null)
        <img class="card-img-top" src="assets/images/default.png" style="height: 300px;">
        @else
        <img class="card-img-top" src="{{ asset('storage/' . $event->banner_url) }}" style="height: 300px;width: 100%; object-fit: cover;">
        @endif
        <div class="card-body">
          <h5 class="card-title">{{ $event->name }}</h5>
          <p class="card-text">{{ \Illuminate\Support\Str::limit($event->description, 100, '...') }}</p>
          <a onClick='EventDetailsModal({{ $event->id }} , "{{ $event->name }}", @json($event->start_date), @json($event->end_date), "{{ date("H:i", strtotime($event->start_time)) }}", "{{ date("H:i", strtotime($event->end_time)) }}", @json($event->banner_url), @json($event->addresses[0]->name), "{{ route("event.show", $event->id) }}")' class="btn btn-primary" style="color: #fff">Detalhes</a>
        </div>
      </div>
  </div>
  </a>

  @endforeach
</div>

<!-- Create Event Modal -->
<div id="createEventModal" class="modal fade" role="dialog" onload="checkLocationField()">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crie o seu novo evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <form action="{{ route("event.store") }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="name">Nome do seu evento</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
              placeholder="" required value="{{ old("name") }}">
            @error("name")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="row justify-content-between">
            <div class="form-group mb-3 col-5">
              <label for="start_date">Data de Inicio</label>
              <input type="date" id="start_date" name="start_date" class="form-control" placeholder="Data de inicio"
                required value="{{ old("start_date") }}">
              @error("start_date")
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group mb-3 col-5">
              <label for="end_date">Data de Fim</label>
              <input type="date" id="end_date" name="end_date" class="form-control" placeholder="Data de Fim"
                required value="{{ old("end_date") }}">
              @error("end_date")
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="row justify-content-between">
            <div class="form-group mb-3 col-5">
              <label for="start_time">Hora de Inicio</label>
              <input type="time" id="start_time" name="start_time" class="form-control" placeholder="Hora de Inicio"
                required value="{{ old("start_time") }}">
              @error("start_time")
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group mb-3 col-5">
              <label for="end_time">Hora de Fim</label>
              <input type="time" id="end_time" name="end_time" class="form-control" placeholder="Hora de Fim"
                required value="{{ old("end_time") }}">
              @error("end_time")
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="banner">Escolha o banner</label>
            <div class="col-lg-10">
              <input type="file" accept="image/*" class="form-control" id="banner" name="banner" required
                value="{{ old("banner") }}">
            </div>
            @error("banner")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group row">
            <label class="col-lg-6 col-form-label" for="address">Selecione a localizacao</label>
            <div class="col-lg-12">
              <select class="form-control custom-select" id="address" name="address" required
                value="{{ old("address") }}">
                <option value="" selected hidden>Seleciona o local do seu evento!</option>
                @foreach ($addresses as $address)
                <option value="{{ $address->id }}"> {{ $address->name }}</option>
                @endforeach
                <option value="new">Outra...</option>
              </select>
              @error("address")
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div id="newLocationFields" style="display:none;">
            <div class="form-group">
              <label for="newLocation">Enter new Location name</label>
              <input type="text" name="newLocation" id="newLocation" class="form-control"
                placeholder="Centro de Conferencias Joaquim Chissano">
              @error("newLocation")
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="url">Enter new location map URL</label>
              <input type="text" name="url" id="url" class="form-control"
                placeholder="https://example.com" pattern="https://.*">
              @error("url")
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group">
            <label for="name">Descrição</label>
            <textarea class="form-control" id="description" name="description"
              placeholder="Breve descrição do evento" required value="{{ old("description") }}"></textarea>
            @error("description")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" type="submit">Criar Evento</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

{{-- Event Details Modal --}}
<div id="EventDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="EventDetailsModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        {{-- Dates --}}
        <div class="d-flex justify-content-between mb-2">
          {{-- Start date --}}
          <div>
            <span>Data de inicio:</span>
            <span class="text-dark font-weight-bold" id="startDate"></span>
          </div>
          {{-- End date --}}
          <div>
            <span>Data de fim:</span>
            <span class="text-dark font-weight-bold" id="endDate"></span>
          </div>
        </div>

        {{-- Hours --}}
        <div class="d-flex justify-content-between mb-2">
          {{-- Start time --}}
          <div>
            <span>Hora de inicio:</span>
            <span class="text-dark font-weight-bold" id="startTime"></span>
          </div>
          {{-- End time --}}
          <div>
            <span>Hora de fim:</span>
            <span class="text-dark font-weight-bold" id="endTime"></span>
          </div>
        </div>

        {{-- Hours --}}
        <div class="d-flex justify-content-between mb-2">
          {{-- Location --}}
          <div>
            <span>Local: </span>
            <span class="text-dark font-weight-bold" id="Location"></span>
          </div>
          {{-- End time --}}
          <div>
            <a href="" target="_blank" class="text-dark font-weight-bold" id="Banner">Banner</a>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-secondary w-100" id="showEvent">Ver detalhes</a>
      </div>
    </div>

  </div>
</div>

<script>
  function EventDetailsModal(id, name, startDate, endDate, startTime, endTime, banner, location, detailUrl) {
    document.getElementById('EventDetailsModalLabel').innerHTML = name;
    document.getElementById('startDate').innerHTML = startDate;
    document.getElementById('endDate').innerHTML = endDate;
    document.getElementById('startTime').innerHTML = startTime;
    document.getElementById('endTime').innerHTML = endTime;
    document.getElementById('Location').innerHTML = location;
    document.getElementById('Banner').href = '{{ asset("storage/") }}' + banner;
    document.getElementById('showEvent').href = detailUrl;
    $('#EventDetailsModal').modal('show');
  }

  function checkLocationField() {
    var select = document.getElementById('address');
    var selectedLocation = select.value;
    var newLocationFields = document.getElementById('newLocationFields');

    if (selectedLocation === "new") {
      newLocationFields.style.display = 'block';
    } else {
      newLocationFields.style.display = 'none';
    }
  };

  document.getElementById('address').addEventListener('change', checkLocationField);
</script>
@endsection