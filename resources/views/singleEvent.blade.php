@extends("layouts.vertical")

@section("css")
<link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" @endsection
  @section("breadcrumb") 
  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb fs-1">
                <h4 class="breadcrumb-item text-muted fs-4"><a href="/events" class="text-muted">Eventos</a></h4>
                <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $event->name }}</h4>
            </ol>
        </nav>
    </div>

    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-secondary"
          data-toggle="modal" data-target="#submitSchedule"
        >
          <i class='uil uil-upload-alt mr-1'></i>Submeter Programa
        </button>

        <button type="button" class="btn btn-primary"
          data-toggle="modal" data-target="#editEvent"
        >
            <i class='uil uil-edit-alt mr-1'></i>Editar 
        </button>
        
        <button type="button" class="btn btn-danger"
          data-toggle="modal" data-target="#deleteEvent"
        >
          <i class='uil uil-trash-alt mr-1'></i>Apagar
        </button>
      </div>
    </div>


    <div id="editEvent" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title text-capitalize" id="exampleModalLabel">Editar {{ $event->name }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
              </div>

              <div class="modal-body">
                  <form action="{{ route("event.update", $event->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                          <label for="name">Nome do seu evento</label>
                          <input type="text" class="form-control" id="name" name="name"
                            aria-describedby="emailHelp" placeholder="Exemplo: Conferencia de kekeke" value="{{ $event->name }}">
                      </div>
                      <div class="form-group mb-3">
                          <label for="date">Data</label>
                          <input type="date" id="date" name="date" class="form-control"
                            placeholder="Date and Time" value="{{ $event->date }}">
                      </div>

                      <div class="form-group mb-3">
                          <label for="banner">Default file
                              input
                          </label>
                          <div class="col-lg-10">
                              <input type="file" accept="image/*" class="form-control" id="banner"
                                  name="banner">
                          </div>
                      </div>

                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary" type="submit">Editar Evento</button>
                      </div>
                  </form>
              </div>
          </div>

      </div>
    </div>
  
    <div id="deleteEvent" class="modal fade" role="dialog">
      <div class="modal-dialog">
        
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel text-capitalize">Eliminar {{ $event->name }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            Tem certeza que quer eliminar este evento?
          </div>

          {{-- Modal Footer --}}
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <form action="{{ route("event.destroy", $event->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-danger">Eliminar</button>

            </form>
          </div>
        </div>
        
      </div>
    </div>

    <div id="submitSchedule" class="modal fade" role="dialog">
      <div class="modal-dialog">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel text-capitalize">Submeter programa do evento {{ $event->name }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("schedule.create", $event->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                <label for="name" class="col-lg-8 col-form-label">Nomeie o seu program</label>
                <input type="text" name="name" id="name" class="form-control">
              </div>

              <div class="form-group row">
                <label for="date" class="col-lg-8 col-form-label">Escolha a data do programa</label>
                <input type="date" name="date" id="date" class="form-control">
              </div>

              <div class="form-group row">
                <label for="schedule" class="col-lg-8 col-form-label">Submeta o programa em PDF</label>
                <input type="file" name="schedule" id="schedule" class="form-control">
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

  </div>

  <div style="height: 2px" class="bg-white rounded w-100 mb-4"></div>
@endsection
  @section("content") 

  <div class="row justify-content-between">
    <div class="col-md-4">
        <h1 class="text-capitalize">{{ $event->name }}</h1>
        <h5 class="d-flex align-items-strecth">
            Data de Inicio:
            <span class="ml-1">{{ $event->date }}</span>
        </h5>
        <h5 class="d-flex align-items-strecth">
            Data de Fim:
            <span class="ml-1">{{ $event->date }}</span>
        </h5>

        <h5 class="d-flex align-items-strecth">
            Localizacao:
            <span class="ml-1">{{ "UJC" }}</span>
        </h5>

        <h5 class="d-flex align-items-strecth">
            Periodo de tempo
            <span class="ml-1">08:00 - 12:00</span>
        </h5>

        <div class="d-flex align-items-center">
          <h5 class="mr-2">Programas:</h5>
          <div>

            @if ($event->schedules->count() == 0)
              <span class="text-danger font-size-14">Ainda nao temos o programa deste evento!</span>
            @endif

            @foreach ($event->schedules as $schedule)
              <a href="{{ asset("storage/schedules/" . $schedule->pdf_url) }}" target="_blank"class="btn btn-secondary">{{ $schedule->name }}</a>
            @endforeach
          </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="w-100 mx-auto border border-dark rounded"
            style="height: 180px; background-image: url('{{ asset("storage/" . $event->banner_url) }}'); background-size: cover">
        </div>
    </div>
  </div>

  <div style="height: 3px" class="bg-white rounded w-100 my-4"></div>

  <div class="row justify-content-between">
    <div class="col-md-6">
      <h2>Participantes</h2>
    </div>

    @if (session("success"))
      <div class="alert alert-success">
        {{ session("success") }}
      </div>
    @endif

    @if (session()->has("message"))
      <div class="alert alert-success">
          {{ session()->get("message") }}
      </div>
    @endif


    <div class="col-12 col-md-2">
      <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addParticipant">
        Adicionar Participante
      </button>

      <div id="addParticipant" class="modal fade"  role=dialog>
        <div class="modal-dialog">
          {{-- Modal content --}}
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-capitalize" id="exampleModalLabel">Criar Participante</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
              <form action="{{ route("inviteParticipant", $event->id) }}" method="POST" >
                @csrf
                <div class="form-group">
                  <label for="participant">Nome do Participante</label>
                  <select class="form-control inline_directive custom-select"
                    name="participant" id="participant"
                  >
                    @foreach ($participants as $participant)
                      @if (!$participant->hasEvent($event->id))
                        <option value="{{ $participant->id }}">{{ $participant->name }}</option>
                      @endif
  @endforeach
</select>
@error("participant")
  <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
@enderror
</div>
<div class="form-group">
  <label for="participant">Tipo de Participante</label>
  <select class="form-control @error("participant") is-invalid @enderror custom-select" name="type" id="type">
    @foreach ($participant_type as $type)
      <option value="{{ $type->id }}">{{ $type->name }}</option>
    @endforeach
  </select>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
  <button type="submit" class="btn btn-primary">Adicionar Participante</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>

</div>
<div class="row mt-4">

  <div class="col-md-12">
    <table class="table table-bordered">
      <thead>
        <tr class="table-light">
          <th scope="col">
            QR
          </th>
          <th scope="col">
            Nome
          </th>
          <th scope="col">
            Descricao
          </th>
          <th scope="col">
            Email
          </th>
          <th scope="col">
            Celular
          </th>
          <th scope="col">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($event->participants as $participante)
          <tr
            class="
          @if ($participante->pivot->status == "Presente") table-success 
          @elseif ($participante->pivot->status == "Em espera")table-primary 
          @elseif ($participante->pivot->status == "Confirmada")table-info
          @elseif ($participante->pivot->status == "Participou")table-success
          @elseif ($participante->pivot->status == "Rejeitada")table-warning
          @elseif ($participante->pivot->status == "Ausente")table-danger @endif
          ">
            <td> <img src="{{ asset("storage/qrcodes/" . $participante->pivot->qr_url . ".svg") }}" width="40"
                alt=""></td>
            <td>{{ $participante->name }}</td>
            <td>{{ $participante->description }}</td>
            <td>{{ $participante->email }}</td>
            <td>{{ $participante->phone_number }}</td>
            <td>

              <a class="btn btn-danger p-2 participant_modal"
                href="{{ route("invite.delete", ["eventid" => $event->id, "participantid" => $participante->id]) }}">
                <i class='uil uil-trash-alt'></i> Remover
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{-- <div id="deleteParticipant" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="user"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          <div class="modal-body">

            Tem certeza que quer eliminar este Convidado?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <form id="deleteForm" method="POST" action="{{ route("invite.destroy") }}">
              @csrf
              <input type="hidden" id="participant_id" name="participant_id" value="" />
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
</div>
<script>
  var removeModal = document.getElementById('deleteParticipant');
  removeModal.addEventListener('show.bs.modal', function(e) {
    // Button that trigerred the modal
    var Button = e.relatedTarget;

    //Extract info from data attributes
    var id = button.getAttribute('id');

    var idInput = removeModal.querySelector('.modal #participant_id');

    idInput.value = id;

  })
</script>

@endsection
