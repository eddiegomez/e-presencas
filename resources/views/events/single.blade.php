@extends("layouts.vertical")
<style>
  .btn-with-icon {
    position: relative;
    padding: 10px 20px;
    /* Adjust padding as needed */
    display: inline-block;
  }

  .remove-icon {
    position: relative;
    top: -15px;
    /* Adjust as needed */
    right: -18px;
    /* Adjust as needed */
    font-size: 1.2rem;
    /* Adjust size as needed */
    color: white;
    /* Change color as needed */
  }
</style>

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
      <div class="col-12 mb-1">
        <h4>
          {{ $event->name }}
        </h4>
      </div>
      {{-- Banner --}}
      <div class="col-12 mb-1">
        <div class="w-100 mx-auto border border-dark rounded"
          style="height: 150px; background-image: url('{{ asset("storage/" . $event->banner_url) }}'); background-size: contain">
        </div>
      </div>
      {{-- Starting Date --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Data de Inicio:
        </span>
      </div>
      <div class="col-9  mt-1">
        <span>
          {{ $event->start_date }}
        </span>
      </div>
      {{-- Finishing Date --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Data de Fim:
        </span>
      </div>
      <div class="col-9">
        <span>
          {{ $event->end_date }}
        </span>
      </div>
      {{-- Starting Time --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Hora de Inicio:
        </span>
      </div>
      <div class="col-9">
        <span>
          {{ date("H:i", strtotime($event->start_time)) }}
        </span>
      </div>
      {{-- Finishing Time --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Hora de Fim:
        </span>
      </div>
      <div class="col-9">
        <span>
          {{ date("H:i", strtotime($event->end_time)) }}
        </span>
      </div>
      {{-- Address --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Localização:
        </span>
      </div>
      <div class="col-9">
        <span>
          @if ($event->addresses[0]->url)
          <a href="{{ $event->addresses[0]->url }}" target="_blank">{{ $event->addresses[0]->name }}</a>
          @else
          <span class="ml-1">{{ $event->addresses[0]->name }}</span>
          @endif
        </span>
      </div>
      {{-- Agendas --}}
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Programa(s):
        </span>
      </div>
      <div class="col-9">
        @if ($event->schedules->count() == 0)
        <span class="text-danger font-size-14">Nenhum programa anexado ao presente evento!</span>
        @endif

        @foreach ($event->schedules as $schedule)
        <a href="{{ asset('storage/schedules/' . $schedule->pdf_url) }}"
          target="_blank" class="btn btn-secondary" style="margin-right: 10px">
          {{ $schedule->name }}
          <span class="uil uil-minus remove-icon btn-danger"
            style="border-radius: 3rem"
            onclick="showRemoveAgendaModal(event, {{ $schedule->id }})"></span>
        </a>
        @endforeach
      </div>
      <div class="col-3 mb-1">
        <span class="font-weight-bold text-secondary">
          Descrição:
        </span>
      </div>
      <div class="col-12">
        <span>
          {{ $event->description }}
        </span>
      </div>
    </div>
  </div>

  {{-- Event Staff --}}
  <div class="col-12 col-md-6 mt-5">
    <div class="row">
      <div class="card" style="width:100%">
        <div class="card-body">
          <div class="d-flex">
            <div class="col-sm-4 col-xl-6 p-0">
              <h4>Protocolos</h4>
            </div>

            <div class="col-sm-8 col-xl-6 mb-3 p-0">
              <div class="float-sm-right mt-3 mt-sm-0">
                <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                  data-target="#addParticipantModal">
                  <i class='uil uil-plus mr-1'></i>Adicionar
                </button>
              </div>
            </div>
          </div>

          <table id="basic-datatable" class="table dt-responsive nowrap">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Celular</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($staffs as $staff)
              <tr>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>{{ $staff->phone }}</td>
                <td class="text-right">
                  <a class="btn btn-danger p-1" data-toggle="modal" href=""
                    onclick=''>
                    <i class='uil uil-minus'></i>
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
                  @if ($participant->pivot->status == " Presente") table-success
              @elseif ($participant->pivot->status == "Em espera")table-primary
              @elseif ($participant->pivot->status == "Confirmada")table-info
              @elseif ($participant->pivot->status == "Participou")table-success
              @elseif ($participant->pivot->status == "Rejeitada")table-warning
              @elseif ($participant->pivot->status == "Ausente")table-danger @endif">
              <td>
                <img src="{{ asset("storage/qrcodes/" . $participant->pivot->qr_url . ".png") }}" width="40"
                  alt="">
              </td>
              <td>{{ $participant->name }} {{ $participant->last_name }}</td>
              <td>
                @switch($participant->pivot->participant_type_id)
                @case(1)
                Convidado
                @break

                @case(2)
                Orador
                @break

                @default
                {{ null }}
                @endswitch
              </td>
              <td>{{ $participant->email }}</td>
              <td>{{ $participant->phone_number }}</td>
              <td class="text-right">
                <a class="btn btn-secondary p-1" href="" data-toggle="modal"
                  onclick='editParticipantModal({{ $participant->id }}, @json($participant->name." ".$participant->last_name), @json($participant->pivot->participant_type_id), "{{ route("participant.show", $participant->id) }}")'>
                  <i class='uil uil-edit-alt'></i>
                </a>

                <a class="btn btn-danger p-1" data-toggle="modal" href=""
                  onclick='deleteParticipantModal({{ $participant->id }}, @json($participant->name." ".$participant->last_name))'>
                  <i class='uil uil-trash-alt'></i>
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
        Apagar este evento significa remover toda lista de participantes e outros dados relacionados ao mesmo.
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
        <form action='{{ route("event.update", $event->id) }}' method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="name">Nome do programa</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
              placeholder="Nome do programa" required value="{{ $event->name }}">
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

          <!--<div class="form-group mb-3">
            <label for="banner">Escolha o banner</label>
            <div class="col-lg-10">
              <input type="file" accept="image/*" class="form-control" id="banner" name="banner"
                value="{{$event->banner_url}}">
            </div>
            @error("banner")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>-->

          <div class="form-group">
            <label for="banner">Banner actual</label>

            <!-- Display the current banner image if it exists -->
            @if($event->banner_url)
            <div class="mb-3">
              <img src="{{ asset("storage/" . $event->banner_url) }}" alt="Current Banner" class="img-fluid" style="max-width: 50px; height: auto;">
            </div>
            @endif
            <!-- File input for uploading a new banner -->
            <input type="file" accept="image/*" class="form-control" id="banner" name="banner">

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
              placeholder="Breve descrição do evento" required>{{ old('description', $event->description) }}</textarea>
            @error("description")
            <span class="text-danger">{{ $message }}</span>
            @enderror
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
          {{ $event->name }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      {{-- Modal Body --}}
      <div class="modal-body">
        <form action="{{ route("schedule.create", $event->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="name">Nome do programa</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
              placeholder="Nome do programa" required>
            @error("name")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="date">Data do programa</label>
            <input type="date" id="date" name="date" class="form-control" placeholder="Data do programa"
              required>
            @error("date")
            <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label for="schedule">Programa (PDF)</label>
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
  <div class="modal-dialog modal-l">
    {{-- Modal Content --}}
    <div class="modal-content">
      {{-- Modal Header --}}
      <div class="modal-header">
        <h5 class="modal-title text-capitalize" id="addParticipantModalLabel">Adicionar um participante ao evento
          {{ $event->name }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      {{-- Modal Body --}}
      <div class="modal-body">
        <form action="{{ route("invite.store") }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" value="{{ $event->id }}">

          <div class="form-group">
            <label for="participant">Nome do Participante</label>
            <select class="form-control inline_directive participants-selector" name="participant" id="participant"
              data-plugin="custom-select" required onchange="checkParticipantField()">
              <option value="">Selecione um participante</option>
              @foreach ($participants as $participant)
              @if (!$participant->hasEvent($event->id))
              <option value="{{ $participant->id }}">{{ $participant->name }} {{ $participant->last_name }} - {{ $participant->email }}</option>
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
                placeholder="Nome da Entidade Participante">
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
                placeholder="Licenciado em Informatica | Empresa">
              @error("degree")
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
              <label for="description">Descricao</label>
              <input type="text" name="description" id="description" class="form-control"
                placeholder="Descricao da entidade">
              @error("description")
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label for="participant">Tipo de Participante</label>
            <select class="form-control @error(" type") is-invalid @enderror custom-select" name="type"
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

{{-- Update Participant Type --}}
<div id="EditParticipantModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    {{-- Modal Content --}}
    <div class="modal-content">
      {{-- Modal Header --}}
      <div class="modal-header">
        <h5 class="modal-title" id="EditParticipantModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      {{-- Modal Body --}}
      <div class="modal-body">
        @if($participant->organization_id != Auth::user()->organization_id)
        <p class="my-1"><a href="" id="participantInfo">Mudar informações do participante!</a></p>
        @endif
        <form action="{{ route("invite.update", ["eventId" => $event->id]) }}" method="POST"
          id="updateParticipantForm">
          @csrf
          <input type="hidden" name="participant" id="Eparticipant">
          <div class="form-group">
            <label for="participant">Tipo de Participante</label>
            <select class="form-control @error(" type") is-invalid @enderror custom-select" name="type"
              id="Etype" required>
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

{{-- Delete Participant from invited list --}}
<div id="DeleteParticipantModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DeleteParticipantModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <p>Tem certeza que pretende eliminar este participante da lista dos convidados?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <form id="deleteEventForm" method="POST" action='{{ route("invite.delete") }}'>
          @csrf
          <input type="hidden" name="event" id="event" value="{{ $event->id }}">
          <input type="hidden" name="participant" id="Dparticipant">
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    $('[data-plugin="custom-select"]').select2({
      placeholder: "Selecione os participantes",
    });
  });


  function editParticipantModal(id, name, type, participantInfoUrl) {
    document.getElementById('EditParticipantModalLabel').innerHTML = "Alterar o tipo do participante " + name;
    document.getElementById('Etype').value = type;
    document.getElementById('Eparticipant').value = id;
    $('#EditParticipantModal').modal('show');
  }

  // Function that triggers the deletion of a participant modal
  function deleteParticipantModal(id, name) {
    document.getElementById('DeleteParticipantModalLabel').innerHTML = 'Desconvidar ' + name;
    document.getElementById('Dparticipant').value = id;

    $('#DeleteParticipantModal').modal('show');
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

  function showRemoveAgendaModal(event, schedule_id) {
    event.preventDefault(); // Prevents default link behavior
    event.stopPropagation(); // Stops the click event from bubbling up
    window.alert(schedule_id);
  }
</script>
@endsection