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
      <h4 class="mb-1 mt-0">Participantes</h4>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createParticipantModal">
          <i class='uil uil-upload-alt mr-1'></i>Criar Participante
        </button>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection

@section("content")
  <div class="row">
    @foreach ($participants as $participant)
      <div class="col-md-6 col-xl-3 col-12">
        <a href="{{ route("participant.show", $participant->id) }}" class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">
                  {{ $participant->description }}
                </span>
                <h2 class="mb-0">{{ $participant->name }}</h2>
              </div>
              <div class="align-self-center">
                <div id="today-revenue-chart" class="apex-charts"></div>
                <span class="text-success font-weight-bold font-size-18">
                  {{ $participant->events->count() }}
                </span>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  {{-- Create Participant Modal --}}
  <div id="createParticipantModal" class="modal fade" role=dialog>
    <div class="modal-dialog">
      {{-- Modal content --}}
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="createParticipantModalLabel">Dados Participante</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>

        {{-- Modal Body --}}
        <div class="modal-body">
          <form action="{{ route("participant.store") }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="name">Nome</label>
              <input type="text" class="form-control @error("name") is-invalid @enderror" id="name" name="name"
                aria-describedby="emailHelp" placeholder="Exemplo: Conferencia de kekeke" value="{{ old("name") }}">

              @error("name")
                <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
              @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control @error("email") is-invalid @enderror" required id="email"
                name="email" aria-describedby="emailHelp" placeholder="JohnDoe@inage.gov.mz"
                value="{{ old("email") }}">
              @error("email")
                <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
              @enderror
            </div>
            {{-- Phone number input --}}
            <div class="form-group">
              <label for="phone_number" class="col-form-label">Numero de Telefone</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">+258</div>
                </div>
                <input type="text" class="form-control  @error("phone") is-invalid @enderror" id="phone"
                  name="phone" placeholder="84 000 0000" autocomplete="off" value="{{ old("phone") }}">
              </div>
              @error("phone")
                <p class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></p>
              @enderror
            </div>

            {{-- Degree Input --}}
            <div class="form-group">
              <label for="degree">Grau (Posicao ou Profissao)</label>
              <input type="text" class="form-control @error("degree") is-invalid @enderror" id="degree"
                name="degree" placeholder="Exemplo: Ministro das Financas | Engenheiro de Software"
                value="{{ old("degree") }}">

              @error("degree")
                <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
              @enderror
            </div>

            {{-- Description Input --}}
            <div class="form-group">
              <label for="description">Descricao do participante</label>
              <textarea type="text" class="form-control @error("description") is-invalid @enderror" id="description"
                name="description"
                placeholder="Exemplo: Um grande docente com coragem e sempre certo, ninguem discute com ele, ele sempre perfeito.">{{ old("description") }}</textarea>

              @error("description")
                <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
              @enderror
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Criar Participante</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
