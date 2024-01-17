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
          onClick='EventDetailsModal({{ $event->id }} , "{{ $event->name }}", @json($event->start_date), @json($event->end_date), @json($event->banner_url), @json($event->addresses[0]->name))'
          style="cursor:pointer">
          <div class="card-body p-0 z-10">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">{{ $event->name }}</span>
                <h2 class="mb-0">{{ $event->participants->count() }}</h2>
              </div>
              <div class="align-self-center">
                <span class="icon-lg icon-dual-primary" data-feather="user"></span>
              </div>
            </div>
          </div>
          <div class="position-absolute top-0 start-0 overlay"></div>
        </a>
      </div>
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
                placeholder="Exemplo: Conferencia de kekeke" required value="{{ old("name") }}">
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
                    <option value = "{{ $address->id }}"> {{ $address->name }}</option>
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
              <a href="" target="_blank"class="text-dark font-weight-bold" id="Banner">Banner</a>
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
    function EventDetailsModal(id, name, startDate, endDate, banner, location) {
      document.getElementById('EventDetailsModalLabel').innerHTML = name;
      document.getElementById('startDate').innerHTML = startDate;
      document.getElementById('endDate').innerHTML = endDate;
      document.getElementById('startTime').innerHTML = '{{ date("H:i", strtotime($event->start_time)) }}';
      document.getElementById('endTime').innerHTML = '{{ date("H:i", strtotime($event->end_time)) }}';
      document.getElementById('Location').innerHTML = location;
      document.getElementById('Banner').href = '{{ asset("storage/" . $event->banner_url) }}';
      document.getElementById('showEvent').href = '{{ route("event.show", $event->id) }}';
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
