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
        <h4 class="breadcrumb-item text-muted fs-4"><a href="{{ route("participant.index") }}" class="text-muted">Participante</a></h4>
        <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $participant->name }}</h4>
      </ol>
    </nav>
  </div>
  <div class="col-sm-8 col-xl-6">
    <div class="float-sm-right mt-3 mt-sm-0">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editParticipantModal">
        <i class='uil uil-edit-alt mr-1'></i>
        Editar Participante
      </button>
      {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteParticipantModal">
          <i class='uil uil-trash-alt mr-1'></i>
          Remover Participante
        </button> --}}
    </div>
  </div>
</div>

{{-- Divider line --}}
<div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection

@hasrole("gestor")
@section("content")
<div class="col-12">
  <div class="row justify-content-between">
    <div class="card col-12 col-md-7">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <h5>
              <span class="text-muted">Nome do Participante: </span>
              <span>{{ $participant->name }}</span>
            </h5>
            <h5>
              <span class="text-muted">Grau: </span>
              <span>{{ $participant->degree }}</span>
            </h5>
            <h5>
              <span class="text-muted">Contacto: </span>
              <span>+258 {{ mb_substr($participant->phone_number, 0, 2) }}
                {{ substr($participant->phone_number, 2, 3) }}
                {{ substr($participant->phone_number, 5, 4) }} {{ substr($participant->phone_number, 9, 4) }}</span>
            </h5>
            <h5>
              <span class="text-muted">Email: </span>
              <span>{{ $participant->email }}</span>
            </h5>
          </div>
          <div class="col-md-2">
            <img class="mt-2" data-target="#displayQrcode" data-toggle="modal" src="data:image/png;base64,{{base64_encode(QrCode::color(0, 0, 0)->style('round')->eye('circle')->size(112)->format('png')->merge('/public/logo.png',0.3,)->errorCorrection('H')->generate('https://assiduidade.inage.gov.mz/businessCard/'. base64_encode($participant->email)))}}">
          </div>
        </div>
      </div>
    </div>
    <div class="card col-12 col-md-4">
      <div class="card-body">
        <h5>
          <span>Descricao</span>
        </h5>
        <p class="text-muted">
          {{ $participant->description }}
        <p>
      </div>
    </div>
    <div id="displayQrcode" class="modal fade" role=dialog>
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal content --}}
        <div class="modal-content">
          <div class="modal-body">
            <center>
              <h3 class="mt-5 mb-3">{{$participant->name}}</h3>
              <img src="data:image/png;base64,{{base64_encode(QrCode::color(0, 0, 0)->style('round')->eye('circle')->size(412)->format('png')->merge('/public/logo.png',0.3,)->errorCorrection('H')->generate('https://assiduidade.inage.gov.mz/businessCard/'. base64_encode($participant->email)))}}" alt="QR Code with Merged Image">
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Divider line --}}
<div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>

{{-- Table --}}
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="d-flex">
        <div class="col-sm-4 col-xl-6 p-0">
          <h4>Eventos</h4>
        </div>

        <div class="col-sm-8 col-xl-6 mb-3 p-0">
          <div class="float-sm-right mt-3 mt-sm-0">
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#addEventsModal">
              <i class='uil uil-plus mr-1'></i>Adicionar Evento
            </button>
          </div>
        </div>
      </div>

      <table id="basic-datatable" class="table dt-responsive nowrap">
        <thead>
          <tr class="table-dark">
            <th>QR</th>
            <th>Nome</th>
            <th>Convidado como</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($participant->events as $event)
          <tr>
            <td>
              <img src="{{ asset("storage/qrcodes/" . $event->pivot->qr_url . ".svg") }}" width="40" alt="">
            </td>

            <td>
              <a href="{{ route("event.show", $event->id) }}" class="text-body text-capitalize">
                {{ $event->name }}
              </a>
            </td>
            <td>
              @switch($event->pivot->participant_type_id)
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
            <td>
              <span class="badge py-1 font-size-13
                      @if ($event->pivot->status == " Presente") badge-soft-success @elseif ($event->pivot->status == "Em espera") badge-soft-warning
                @elseif ($event->pivot->status == "Confirmada") badge-soft-info
                @elseif ($event->pivot->status == "Participou") badge-soft-success
                @elseif ($event->pivot->status == "Rejeitada")badge-soft-warning
                @elseif ($event->pivot->status == "Ausente") badge-soft-danger @endif
                ">
                {{ $event->pivot->status }}
              </span>
            </td>
            <td class="text-right">
              @if ($event->organization_id == $user->organization_id)
              <a class="btn btn-info p-1 mr-1 participant_modal text-white" href="#" onclick='updateParticipantTypeModal({{ $event->id }}, @json($participant->id), @json($participant->name), @json($event->pivot->participant_type_id) )'>
                <i class='uil uil-edit-alt'></i>
              </a>
              <a class="btn btn-danger p-1 participant_modal text-white" href="#" onclick='showDeleteModal({{ $participant->id }},@json($participant->name), {{ $event->id }},@json($event->name))'>
                <i class='uil uil-trash-alt'></i>
              </a>
              @else
              <span class="badge py-1 font-size-13 badge-soft-warning">---</span>
              @endif
            </td>
          <tr>
            @endforeach
        </tbody>
      </table>

    </div> <!-- end card body-->
  </div> <!-- end card -->
</div><!-- end col-->

{{-- Edit Participant Modal --}}
<div id="editParticipantModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    {{-- Modal Content --}}
    <div class="modal-content">
      {{-- Modal Header --}}
      <div class="modal-header">
        <h5 class="modal-title" id="editParticipantModalLabel">
          Editar o participante de {{ $participant->name }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      {{-- Modal Body --}}
      <div class="modal-body">
        <form action="{{ route("participant.update", $participant->id) }}" method="POST">
          @csrf
          <div class="form-group">

            {{-- Input Participant --}}
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $participant->name }}">
            @error("name")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          {{-- Input degree --}}
          <div class="form-group">
            <label for="description">Grau</label>
            <input type="text" name="degree" id="degree" class="form-control" value="{{ $participant->degree }}">
            @error("degree")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          {{-- Input Cellphone --}}
          <div class="form-group">
            <label for="phone_number">Numero de Telefone</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $participant->phone_number }}">
            @error("phone_number")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          {{-- Input email --}}
          <div class="form-group">
            <label for="email">Email </label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $participant->email }}">
            @error("email")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          {{-- Input Description --}}
          <div class="form-group">
            <label for="description">Bibliografia </label>
            <textarea name="description" id="description" class="form-control">{{ $participant->description }}</textarea>
            @error("description")
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Editar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Delete Participant Modal --}}
<div id="deleteParticipantModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    {{-- Modal Content --}}
    <div class="modal-content">
      {{-- Modal Header --}}
      <div class="modal-header">
        <h5 class="modal-title" id="deleteParticipantModal">
          Apagar o participante de {{ $participant->name }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body">

        <p>Tem certeza que pretende apagar este participante?</p>
        <form action="{{ route("participant.destroy", $participant->id) }}" method="POST">
          @csrf
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Add Events Modal --}}
<div id="addEventsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    {{-- Modal Content --}}
    <div class="modal-content">
      {{-- Modal Header --}}
      <div class="modal-header">
        <h5 class="modal-title" id="addEventsModalLabel">
          Convidar o {{ $participant->name }} para um evento
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body">
        <form action="{{ route("invite.store") }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="id">Nome do Evento</label>
            <select class="form-control inline_directive custom-select" name="id" id="id" required>
              <option selected hidden value="">Selecione um evento</option>
              @foreach ($events as $event)
              @if (!$participant->hasEvent($event->id))
              <option value="{{ $event->id }}">{{ $event->name }}</option>
              @endif
              @endforeach
            </select>
            @error("participant")
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <p class="mt-4">
              <a href="{{ route("event.list") }}">Crie um evento</a>
            </p>

            <div class="form-group">
              <label for="participant">Tipo de Participante</label>
              <select class="form-control @error(" type") is-invalid @enderror custom-select" name="type" id="type" required>
                <option selected hidden value="">Escolha um tipo para o participante</option>
                @foreach ($participant_type as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
              @error("type")
              <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>

            <input type="hidden" name="participant" value="{{ $participant->id }}">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Criar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Delete Event Modal --}}
<div id="deleteInvite" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="user">Remover o nome <strong id="pNome"></strong> do evento
          <strong id="eNome"></strong>?
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">

        <p>Tem certeza que pretende remover o participante <strong id="rmNome"></strong> do(a) <strong id=""></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <form id="removeParticipant" method="POST">
          <input type="hidden" name="_method" value="POST">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="participantId" name="participant" value="" />
          <input type="hidden" id="eventId" name="event" value="" />
          <button type="submit" class="btn btn-danger">Confirmar remoção</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Edit Participant type --}}
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
        <form action="" method="POST" id="updateParticipantForm">
          @csrf
          <input type="hidden" name="event" id="Eevent">
          <input type="hidden" name="participant" id="Eparticipant">
          <div class="form-group">
            <label for="participant">Tipo de Participante</label>
            <select class="form-control @error(" type") is-invalid @enderror custom-select" name="type" id="Etype" required>
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
  function showDeleteModal(participantId, name, eventId, eventName) {
    // Get a reference to the form element
    var form = document.getElementById('removeParticipant');

    // Set the action attribute to the desired URL
    form.action = '{{ route("invite.delete") }}'; // Replace with your desired URL

    document.getElementById('participantId').value = participantId;
    document.getElementById('eventId').value = eventId;
    document.getElementById('rmNome').innerHTML = name;
    document.getElementById('pNome').innerHTML = name;
    document.getElementById('eNome').innerHTML = eventName;
    $("#deleteInvite").modal('show');
  }

  function updateParticipantTypeModal(eventId, participantId, eventName, type) {
    document.getElementById('EditParticipantModalLabel').innerHTML = `Editar o tipo do participante no ${eventName}`;
    document.getElementById('Eparticipant').value = participantId;
    document.getElementById('Eevent').value = eventId;
    document.getElementById('Etype').value = type
    document.getElementById('updateParticipantForm').action =
      `{{ route("invite.update", ["eventId" => $event->id]) }}`;

    $("#EditParticipantModal").modal("show");
  }
</script>
@endsection
@endhasrole