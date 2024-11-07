@extends("layouts.vertical")


@section("css")
<link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("css/style.css") }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
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

@hasrole("gestor")
@section("content")
<div class="row">
  @foreach ($participants as $participant)
  <div class="col-md-6 col-xl-3 col-12">
    <a href="{{ route("participant.show", $participant->id) }}" class="card">
      <div class="card-body p-0">
        <div class="media p-3">
          <div class="media-body">
            <span class="text-muted text-uppercase font-size-12 font-weight-bold">
              {{ $participant->degree }}
            </span>
            <h2 class="mb-0">{{ $participant->name }} {{ $participant->last_name }}</h2>
            <h6 class="mb-0 text-muted">{{ $participant->email }}</h6>
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
        <form action="{{ route("participant.store") }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control @error(" name") is-invalid @enderror" id="name" name="name" aria-describedby="emailHelp" placeholder="Nome" value="{{ old("name") }}">

            @error("name")
            <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
            @enderror
          </div>
          <div class="form-group">
            <label for="last_name">Apelido</label>
            <input type="text" class="form-control @error(" last_name") is-invalid @enderror" id="last_name" name="last_name" aria-describedby="last_name" placeholder="Apelido" value="{{ old("last_name") }}">

            @error("last_name")
            <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
            @enderror
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error(" email") is-invalid @enderror" required id="email" name="email" aria-describedby="emailHelp" placeholder="exemplo@dominio.com" value="{{ old("email") }}">
            @error("email")
            <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
            @enderror
          </div>
          {{-- Phone number input --}}
          <div class="form-group">
            <label for="phone_number" class="col-form-label">Número de Telefone</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">+258</div>
              </div>
              <input type="text" class="form-control  @error(" phone") is-invalid @enderror" id="phone" name="phone" placeholder="" autocomplete="off" value="{{ old("phone") }}">
            </div>
            @error("phone")
            <p class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></p>
            @enderror
          </div>

          {{-- Degree Input --}}
          <div class="form-group">
            <label for="degree">Grau (Posição ou Profissão)</label>
            <input type="text" class="form-control @error(" degree") is-invalid @enderror" id="degree" name="degree" placeholder="" value="{{ old("degree") }}">

            @error("degree")
            <span class="invalid-feedback" role="alert"> <strong> {{ $message }}</strong></span>
            @enderror
          </div>

          {{-- Photo Input --}}
          <div class="form-group">
            <input type="file" accept="image/*" class="form-control" id="upload" name="upload" required value="{{ old('upload') }}">
            <div id="upload-demo"></div>
          </div>

          {{-- Description Input --}}
          <div class="form-group">
            <label for="description">Descrição do participante</label>
            <textarea type="text" class="form-control @error(" description") is-invalid @enderror" id="description" name="description" placeholder="">{{ old("description") }}</textarea>

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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var uploadCrop = new Croppie(document.getElementById('upload-demo'), {
      viewport: {
        width: 200,
        height: 200
      },
      boundary: {
        width: 300,
        height: 300
      },
      showZoomer: true,
      enableResize: false,
      enableOrientation: true,
    });

    function readFile(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          uploadCrop.bind({
            url: e.target.result,
          });
        };
        reader.readAsDataURL(input.files[0]);
      } else {
        console.log("Sorry - you're browser doesn't support the FileReader API");
      }
    }

    document.getElementById('upload').addEventListener('change', function() {
      readFile(this);
    });

    document.getElementById('upload-result').addEventListener('click', function(ev) {
      uploadCrop.result({
        type: 'canvas',
        size: 'viewport',
      }).then(function(blob) {
        // Here, you would typically send the blob to your server with an AJAX request.
        // For demonstration, we'll just log it to the console.
        console.log(blob);
        // Example: AJAX request to server (pseudo code)
        // var formData = new FormData();
        // formData.append('image', blob);
        // fetch('/upload-image', { method: 'POST', body: formData });
      });
    });
  });
</script>
@endsection


@endhasrole