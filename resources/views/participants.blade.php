@extends("layouts.vertical")


@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  <div class= "row page-title align-items-center">
    <div class = "col-sm-4 col-xl-6">
      <nav style= "--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol>
          <h4 class="breadcrumb-item text-muted fs-4"><a href="/events" class="text-muted">Participantes</a></h4>
        </ol>
      </nav>
    </div>

    <div class = "col-sm-4 col-xl-6">
      <div class = "float-sm-right mt-3 sm-0">
        <button type="button" class="btn btn-primary" data-target=#createParticipant data-toggle="modal">
          <i class="uil uil-plus mr-1"></i> Participante
        </button>
      </div>
    </div>

    <div id="createParticipant" class="modal fade" role=dialog>
      <div class="modal-dialog">
        {{-- Modal content --}}
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="exampleModalLabel">Criar Participante</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>

          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("participant.store") }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="name">Nome do Participante</label>
                <input type="text" class="form-control @error("name") is-invalid @enderror" id="name"
                  name="name" aria-describedby="emailHelp" placeholder="Exemplo: Conferencia de kekeke">

                @error("name")
                  <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
                @enderror
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error("email") is-invalid @enderror" required id="email"
                  name="email" aria-describedby="emailHelp" placeholder="JohnDoe@inage.gov.mz">
                @error("email")
                  <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
                @enderror
              </div>
              <div class="form-group">
                <label for="phone">Numero de Telefone</label>
                <input type="text" class="form-control @error("phone_number") is-invalid @enderror" id="phone_number"
                  name="phone_number" aria-describedby="emailHelp" placeholder="84 000 0000">
                @error("phone_number")
                  <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
                @enderror
              </div>
              <div class="form-group">
                <label for="description">Descricao (Posicao ou Profissao)</label>
                <input type="text" class="form-control @error("description") is-invalid @enderror" id="description"
                  name="description" aria-describedby="emailHelp" placeholder="Exemplo: Ministro das Financas">

                @error("email")
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
  </div>

  <div style="height: 2px" class="bg-white rounded w-100 mb-4"></div>
@endsection

@section("content")
  <div class="row page-title align-items-center">

    @foreach ($participants as $participant)
      <div class="col-md-6 col-xl-4">
        <div class="card">
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
        </div>
      </div>
    @endforeach

  </div>
@endsection
