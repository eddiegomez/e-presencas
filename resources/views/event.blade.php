@extends("layouts.vertical")


@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("css/style.css") }}" rel="stylesheet">
@endsection

@section("breadcrumb")
  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <h4 class="mb-1 mt-0">Eventos</h4>
    </div>


    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEvent">
          <i class='uil uil-plus mr-1'></i>
          Criar Evento
        </button>
      </div>
    </div>
  </div>
  <div style="height: 2px" class="bg-white rounded w-100 mb-4">

  </div>
@endsection

@section("content")
  <div class="row">
    <div class="col-md-12 col-xl-12 mb-4">
      <!-- Trigger the modal with a button -->


      <!-- Modal -->

      <div id="createEvent" class="modal fade" role="dialog" onload="checkLocationField()">
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
                    placeholder="Exemplo: Conferencia de kekeke">
                </div>

                <div class="row justify-content-between">
                  <div class="form-group mb-3 col-5">
                    <label for="start_date">Data de Inicio</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                      placeholder="Data de inicio">
                  </div>
                  <div class="form-group mb-3 col-5">
                    <label for="end_date">Data de Fim</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" placeholder="Data de Fim">
                  </div>
                </div>

                <div class="row justify-content-between">
                  <div class="form-group mb-3 col-5">
                    <label for="start_time">Hora de Inicio</label>
                    <input type="time" id="start_time" name="start_time" class="form-control"
                      placeholder="Hora de Inicio">
                  </div>
                  <div class="form-group mb-3 col-5">
                    <label for="end_time">Hora de Fim</label>
                    <input type="time" id="end_time" name="end_time" class="form-control" placeholder="Hora de Fim">
                  </div>
                </div>

                <div class="form-group mb-3">
                  <label for="banner">Escolha o banner</label>
                  <div class="col-lg-10">
                    <input type="file" accept="image/*" class="form-control" id="banner" name="banner">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-lg-6 col-form-label" for="address">Selecione a localizacao</label>
                  <div class="col-lg-12">
                    <select class="form-control custom-select" id="address" name="address">
                      <option value="" selected hidden>Seleciona o local do seu evento!</option>
                      @foreach ($addresses as $address)
                        <option value = "{{ $address->id }}"> {{ $address->name }}</option>
                      @endforeach
                      <option value="new">Outra...</option>
                    </select>
                  </div>
                </div>

                <div id="newLocationFields" style="display:none;">
                  <div class="form-group">
                    <label for="newLocation">Enter new Location name</label>
                    <input type="text" name="newLocation" id="newLocation" class="form-control"
                      placeholder="Centro de Conferencias Joaquim Chissano">
                  </div>
                  <div class="form-group">
                    <label for="url">Enter new location map URL</label>
                    <input type="text" name="url" id="url" class="form-control"
                      placeholder="https://example.com" pattern="https://.*">
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
    </div>

    @foreach ($events as $event)
      <div class="col-md-6 col-xl-3">
        <a href="/event/{{ $event->id }}" class="card position-relative">
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

  <script>
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
