@extends("layouts.vertical")

@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("css/style.css") }}" rel="stylesheet">
@endsection



@section("breadcrumb")
  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb fs-1">
          <h4 class="breadcrumb-item text-muted fs-4"><a href="/participants" class="text-muted">Participantes</a></h4>
          <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $participant->name }}</h4>
        </ol>
      </nav>
    </div>


    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editParticipant">
          <i class='uil uil-edit-alt mr-1'></i>
          Editar Participante
        </button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteParticipant">
          <i class='uil uil-trash-alt mr-1'></i>
          Remover Participante
        </button>
      </div>
    </div>
    {{-- <div class="col-sm-8 col-xl-6">
        <form class="form-inline float-sm-right mt-3 mt-sm-0">
            <div class="form-group mb-sm-0 mr-2">
                <input type="text" class="form-control" id="dash-daterange" style="min-width: 190px;" />
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class='uil uil-file-alt mr-1'></i>Download
                    <i class="icon"><span data-feather="chevron-down"></span></i></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item notify-item">
                        <i data-feather="mail" class="icon-dual icon-xs mr-2"></i>
                        <span>Email</span>
                    </a>
                    <a href="#" class="dropdown-item notify-item">
                        <i data-feather="printer" class="icon-dual icon-xs mr-2"></i>
                        <span>Print</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item notify-item">
                        <i data-feather="file" class="icon-dual icon-xs mr-2"></i>
                        <span>Re-Generate</span>
                    </a>
                </div>
            </div>
        </form>
    </div> --}}
  </div>

  <div style="height: 2px" class="bg-white rounded w-100 mb-4"></div>

  <div id="deleteParticipant" class="modal fade" role="dialog">
    <div class="modal-dialog">
      {{-- Modal Content --}}
      <div class="modal-content">
        {{-- Modal Header --}}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel text-capitalize">
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
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Edit Participant Modal --}}

  <div id="editParticipant" class="modal fade" role="dialog">
    <div class="modal-dialog">
      {{-- Modal Content --}}
      <div class="modal-content">
        {{-- Modal Header --}}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel text-capitalize">
            Editar o participante de {{ $participant->name }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        {{-- Modal Body --}}
        <div class="modal-body">
          <form action="{{ route("participant.update", $participant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <label for="name" class="col-lg-8 col-form-label">Nome</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ $participant->name }}">
            </div>

            <div class="form-group row">
              <label for="description" class="col-lg-8 col-form-label">Bio</label>
              <input type="text" name="description" id="description" class="form-control"
                value="{{ $participant->description }}">
            </div>

            <div class="form-group row">
              <label for="phone_number" class="col-lg-8 col-form-label">Numero de Telefone</label>
              <input type="text" name="phone_number" id="phone_number" class="form-control"
                value="{{ $participant->phone_number }}">
            </div>
            <div class="form-group row">
              <label for="email" class="col-lg-8 col-form-label">Email </label>
              <input type="email" name="email" id="email" class="form-control" value="{{ $participant->email }}">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Eliminar</button>

            </div>
          </form>
        </div>

        {{-- Modal Footer --}}
      </div>

    </div>
  </div>
@endsection


@section("content")
  @if (session("success"))
    <div class="alert-success alert">
      {{ session()->get("success") }}
    </div>
  @endif

  <div class="row">
    <div class="col-12 col-md-8">
      <h5>
        <span class="text-muted">Nome do Participante: </span>
        <span>{{ $participant->name }}</span>
      </h5>
      <h5>
        <span class="text-muted">Bio: </span>
        <span>{{ $participant->description }}</span>
      </h5>
      <h5>
        <span class="text-muted">Contacto: </span>
        <span>+258 {{ $participant->phone_number }}</span>
      </h5>
      <h5>
        <span class="text-muted">Email: </span>
        <span>{{ $participant->email }}</span>
      </h5>
    </div>
  </div>

  <div style="height: 2px" class="bg-white rounded w-100 my-4"></div>

  <div class="row">
    <div class="col-12 mb-2">
      <h3>Eventos</h3>
    </div>
    <div class="col-12">
      <div class="table-responsive-md">
        <table class="table table-bordered">
          <thead>
            <tr class="table-light">
              <th scope="col">
                QR
              </th>
              <th scope="col">
                Nome do evento
              </th>
              <th scope="col">
                Convidado como
              </th>
              <th scope="col">
                Status
              </th>
              <th class="text-right" scope="col">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>

            @foreach ($participant->events as $event)
              <tr>
                <td>
                  <img src="{{ asset("storage/qrcodes/" . $event->pivot->qr_url . ".svg") }}" width="40"
                    alt="">
                </td>

                <td>
                  <a href="#" class="text-body">
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
                  {{ $event->pivot->status }}
                </td>
                <td class="text-right">
                  <a class="btn btn-danger p-2 participant_modal"
                    onclick='showDeleteModal({{ $participant->id }},@json($participant->name), {{ $event->id }},@json($event->name))'>
                    <i class='uil uil-trash-alt'></i> Remover
                  </a>
                </td>
              <tr>
            @endforeach
          </tbody>
        </table>

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

                <p>Tem certeza que pretende remover o participante <strong id="rmNome"></strong> do(a) <strong
                    id="">{{ $event->name }}</strong>?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="removeParticipant" method="POST">
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" id="participantId" name="participantId" value="" />
                  <input type="hidden" id="eventId" name="eventId" value="" />
                  <button type="submit" class="btn btn-danger">Confirmar remoção</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showDeleteModal(participantId, name, eventId, eventName) {
      // Get a reference to the form element
      var form = document.getElementById('removeParticipant');

      // Set the action attribute to the desired URL
      form.action = "{{ route("removerParticipante") }}"; // Replace with your desired URL

      document.getElementById('participantId').value = participantId;
      document.getElementById('eventId').value = eventId;
      document.getElementById('rmNome').innerHTML = name;
      document.getElementById('pNome').innerHTML = name;
      document.getElementById('eNome').innerHTML = eventName;
      $("#deleteInvite").modal('show');
    }
  </script>
@endsection
