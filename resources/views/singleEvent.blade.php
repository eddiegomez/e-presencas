@extends('layouts.vertical')

@section('css')
<link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" @endsection
    @section('breadcrumb') 
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
          <button type="button" class="btn btn-primary"
            data-toggle="modal" data-target="#editEvent"
          >
              <i class='uil uil-edit-alt mr-1'></i>Edit 
          </button>
          <button type="button" class="btn btn-danger"
            data-toggle="modal" data-target="#deleteEvent"
          >
            <i class='uil uil-trash-alt mr-1'></i>Delete
          </button>
          
          {{-- Modal --}}
          

          

          {{-- Modal --}}
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
                        <form action="{{ route('event.update', $event->id)}}" method="POST" enctype="multipart/form-data">
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
                                    input</label>
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
  
          </div>

          <div id="deleteEvent" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel text-capitalize">Eliminar {{ $event->name }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                  Tem certeza que quer eliminar este evento?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <form action="{{route("event.destroy", $event->id)}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Eliminar</button>

                  </form>
                </div>
              </div>
            </div>
          </div>

          <div style="height: 2px" class="bg-white rounded w-100 mb-4">

    </div>
@endsection


@section('content') 

<script src="{{asset ('js/handleModals.js')}}"></script>
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
            <span class="ml-1">{{ 'UJC' }}</span>
        </h5>

        <h5 class="d-flex align-items-strecth">
            Periodo de tempo
            <span class="ml-1">08:00 - 12:00</span>
        </h5>

    </div>

    <div class="col-md-6">
        <div class="w-100 mx-auto border border-dark rounded"
            style="height: 180px; background-image: url('{{ asset('storage/' . $event->banner_url) }}'); background-size: cover">
        </div>
    </div>
  </div>

  <div style="height: 3px" class="bg-white rounded w-100 my-4"></div>

  <div class="row justify-content-between">
    <div class="col-md-6">
      <h2>Participantes</h2>
    </div>

    <div class="col-md-4">
      <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addParticipant">
        Adicionar Participante
      </button>
    </div>

  </div>
  <div class="row mt-4">

    <div class="col-md-12">
      <table class="table table-bordered">
        <thead>
          <tr class="table-light">
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
          <tr>
            <td>John Doe</td>
            <td>Ministro da Educaocao</td>
            <td>Doe@gov.mz</td>
            <td>+258 84 000 0000</td>
            <td>
              <button type="button" class="btn btn-danger p-2 participant_modal"
                data-toggle="modal" data-target="#deleteParticipant"
                data-delete-link="{{}}"
              >
                <i class='uil uil-trash-alt'></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div id="deleteParticipant" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel text-capitalize">Retirar nome dos Participantes?</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="participant_id" name="participant_id" value=""/>
                  Tem certeza que quer eliminar este Convidado?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  <form id="deleteForm" method="POST" action="">
                    <button type="button" class="btn btn-danger">Eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
@endsection
