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
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb fs-1">
          <h4 class="breadcrumb-item text-muted fs-4"><a href="{{ route("event.list") }}" class="text-muted">Eventos</a>
          </h4>
          <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $event->name }}</h4>
        </ol>
      </nav>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#submitSchedule">
          <i class='uil uil-upload-alt mr-1'></i>Submeter Programa
        </button>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateEventModal">
          <i class='uil uil-edit-alt mr-1'></i>Editar
        </button>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteEventModal">
          <i class='uil uil-trash-alt mr-1'></i>Apagar
        </button>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection

@section("content")
  <div class="row">
    {{-- Event data --}}
    <div class="col-12 col-md-6">
      <div class="row justify-content-between">
        {{-- Name of the event --}}
        <div class="col-12 mb-2">
          <h4>
            {{ $event->name }}
          </h4>
        </div>
        {{-- Starting Date --}}
        <div class="col-6 mb-1">
          <span class="font-weight-bold text-secondary">
            Data de Inicio
          </span>
        </div>
        <div class="col-6">
          <span>
            {{ $event->start_date }}
          </span>
        </div>
        {{-- Finishing Date --}}
        <div class="col-6 mb-1">
          <span class="font-weight-bold text-secondary">
            Data de Fim
          </span>
        </div>
        <div class="col-6">
          <span>
            {{ $event->end_date }}
          </span>
        </div>
        {{-- Starting Time --}}
        <div class="col-6 mb-1">
          <span class="font-weight-bold text-secondary">
            Hora de Inicio
          </span>
        </div>
        <div class="col-6">
          <span>
            {{ date("H:i", strtotime($event->start_time)) }}
          </span>
        </div>
        {{-- Finishing Time --}}
        <div class="col-6 mb-1">
          <span class="font-weight-bold text-secondary">
            Hora de Fim
          </span>
        </div>
        <div class="col-6">
          <span>
            {{ date("H:i", strtotime($event->end_time)) }}
          </span>
        </div>
        {{-- Finishing Time --}}
        <div class="col-6 mb-1">
          <span class="font-weight-bold text-secondary">
            Localização
          </span>
        </div>
        <div class="col-12">
          <span>
            @if ($event->addresses[0]->url)
              <a href="{{ $event->addresses[0]->url }}" target="_blank">{{ $event->addresses[0]->name }}</a>
            @else
              <span class="ml-1">{{ $event->addresses[0]->name }}</span>
            @endif
          </span>
        </div>
      </div>
    </div>

    {{-- Event banner and schedules --}}
    <div class="col-12 col-md-6">
      <div class="row">
        {{-- Banner --}}
        <div class="col-12">
          <div class="w-100 mx-auto border border-dark rounded"
            style="height: 150px; background-image: url('{{ asset("storage/" . $event->banner_url) }}'); background-size: contain">
          </div>
        </div>

        {{-- Schedules --}}
        <div class="col-12 text-center mt-4 ">
          @if ($event->schedules->count() == 0)
            <span class="text-danger font-size-14">Ainda nao temos o programa deste evento!</span>
          @endif

          @foreach ($event->schedules as $schedule)
            <a href="{{ asset("storage/schedules/" . $schedule->pdf_url) }}"
              target="_blank"class="btn btn-secondary">{{ $schedule->name }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 my-3"></div>

  {{-- Participants Table --}}
  <div class="row">
    {{-- Table --}}
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex">
            <div class="col-sm-4 col-xl-6 p-0">
              <h4>Participantes</h4>
            </div>

            <div class="col-sm-8 col-xl-6 mb-3 p-0">
              <div class="float-sm-right mt-3 mt-sm-0">
                <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                  data-target="#addParticipantModal">
                  <i class='uil uil-plus mr-1'></i>Adicionar Participante
                </button>
              </div>
            </div>
          </div>

          <table id="basic-datatable" class="table dt-responsive nowrap">
            <thead>
              <tr>
                <th>QR</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Email</th>
                <th>Celular</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($event->participants as $participant)
                <tr
                  class="
                  @if ($participant->pivot->status == "Presente") table-success 
                  @elseif ($participant->pivot->status == "Em espera")table-primary 
                  @elseif ($participant->pivot->status == "Confirmada")table-info
                  @elseif ($participant->pivot->status == "Participou")table-success
                  @elseif ($participant->pivot->status == "Rejeitada")table-warning
                  @elseif ($participant->pivot->status == "Ausente")table-danger @endif">
                  <td>
                    <img src="{{ asset("storage/qrcodes/" . $participant->pivot->qr_url . ".svg") }}" width="40"
                      alt="">
                  </td>
                  <td>{{ $participant->name }}</td>
                  <td>{{ $participant->description }}</td>
                  <td>{{ $participant->email }}</td>
                  <td>{{ $participant->phone_number }}</td>
                  <td>
                    <a class="btn btn-danger p-2 participant_modal"
                      href="{{ route("invite.destroy", ["eventid" => $event->id, "participantid" => $participant->id]) }}">
                      <i class='uil uil-trash-alt'></i> Remover
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div> <!-- end card body-->
      </div> <!-- end card -->
    </div><!-- end col-->

  </div>

  {{-- Delete the Event Modal --}}
  <div id="deleteEventModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteEventModalLabel">Tem certeza que deseja apagar este evento?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          Apagar este evento significa eliminar toda lista de participantes e outros dados relacionados ao mesmo.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <form id="deleteEventForm" method="POST" action='{{ route("event.destroy", $event->id) }}'>
            @csrf
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Update the Event Modal --}}
  <div id="updateEventModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateEventModalLabel">Editar {{ $event->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <form action='{{ route("event.update", $event->id) }}' method="POST">
            @csrf
            <div class="form-group">
              <label for="name">Nome do seu evento</label>
              <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                placeholder="Exemplo: Conferencia de kekeke" required value="{{ $event->name }}">
              @error("name")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="row justify-content-between">
              <div class="form-group mb-3 col-5">
                <label for="start_date">Data de Inicio</label>
                <input type="date" id="start_date" name="start_date" class="form-control"
                  placeholder="Data de inicio" required value="{{ $event->start_date }}">
                @error("start_date")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-3 col-5">
                <label for="end_date">Data de Fim</label>
                <input type="date" id="end_date" name="end_date" class="form-control" placeholder="Data de Fim"
                  required value="{{ $event->end_date }}">
                @error("end_date")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="row justify-content-between">
              <div class="form-group mb-3 col-5">
                <label for="start_time">Hora de Inicio</label>
                <input type="time" id="start_time" name="start_time" class="form-control"
                  placeholder="Hora de Inicio" required value="{{ $event->start_time }}">
                @error("start_time")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-3 col-5">
                <label for="end_time">Hora de Fim</label>
                <input type="time" id="end_time" name="end_time" class="form-control" placeholder="Hora de Fim"
                  required value="{{ $event->end_time }}">
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
                  value="{{ $event->addresses[0]->id }}">
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
              <button type="submit" class="btn btn-primary" type="submit">Editar Evento</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Submit Event Schedule Modal --}}
  <div id="submitSchedule" class="modal fade" role="dialog">
    <div class="modal-dialog">
      {{-- Modal Content --}}
      <div class="modal-content">
        {{-- Modal Header --}}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel text-capitalize">Submeter programa do evento
            {{ $event->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        {{-- Modal Body --}}
        <div class="modal-body">
          <form action="{{ route("schedule.create", $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="name">Nomeie o seu programa</label>
              <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                placeholder="Exemplo: Conferencia de kekeke" required>
              @error("name")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="date">Escolha a data do programa</label>
              <input type="date" id="date" name="date" class="form-control" placeholder="Data do programa"
                required>
              @error("date")
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group mb-3">
              <label for="schedule">Submeta o programa em PDF</label>
              <div>
                <input type="file" accept="application/pdf" class="form-control" id="schedule" name="schedule"
                  required>
              </div>
              @error("schedule")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Submeter</button>

            </div>
          </form>
        </div>

        {{-- Modal Footer --}}
      </div>

    </div>
  </div>

  {{-- Add participant --}}
  <div id="addParticipantModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      {{-- Modal Content --}}
      <div class="modal-content">
        {{-- Modal Header --}}
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="addParticipantModalLabel">Adicionar um participante ao evento
            {{ $event->name }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        {{-- Modal Body --}}
        <div class="modal-body">
          <form action="{{ route("inviteParticipant", ["id" => $event->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="participant">Nome do Participante</label>
              <select class="form-control inline_directive custom-select" name="participant" id="participant" required>
                <option selected hidden value="">Selecione um participante</option>
                @foreach ($participants as $participant)
                  @if (!$participant->hasEvent($event->id))
                    <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                  @endif
                @endforeach
                <option value="new">Outro</option>
              </select>

              @error("participant")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div id="newParticipantFields" style="display:none;">

              {{-- Nome do participant --}}
              <div class="form-group">
                <label for="newParticipant">Nome do Participante</label>
                <input type="text" name="name" id="name" class="form-control"
                  placeholder="Nome do Participante">
                @error("name")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Email --}}
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control"
                  placeholder="john.doe@gmail.com">
                @error("email")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Phone number --}}
              <div class="form-group">
                <label for="phone_number">Numero de telefone</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control"
                  placeholder="84 000 0000">
                @error("phone_number")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Degree --}}
              <div class="form-group">
                <label for="degree">Grau</label>
                <input type="text" name="degree" id="degree" class="form-control"
                  placeholder="Licenciado em Bontxo">
                @error("degree")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Description --}}
              <div class="form-group">
                <label for="description">Descricao</label>
                <input type="text" name="description" id="description" class="form-control"
                  placeholder="john.doe@gmail.com">
                @error("description")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="form-group">
              <label for="participant">Tipo de Participante</label>
              <select class="form-control @error("type") is-invalid @enderror custom-select" name="type"
                id="type" required>
                <option selected hidden value="">Escolha um tipo para o participante</option>
                @foreach ($participant_type as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
              @error("type")
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Submeter</button>

            </div>
          </form>
        </div>
      </div>
    </div>
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

    function checkParticipantField() {
      var select = document.getElementById('participant');
      var selectedLocation = select.value;
      var newLocationFields = document.getElementById('newParticipantFields');

      if (selectedLocation === "new") {
        newLocationFields.style.display = 'block';
      } else {
        newLocationFields.style.display = 'none';
      }
    };

    document.getElementById('participant').addEventListener('change', checkParticipantField);
  </script>
@endsection
