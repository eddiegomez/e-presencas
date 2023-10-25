@extends('layouts.vertical')


@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <div class="row page-title align-items-center">
        <div class="col-sm-4 col-xl-6">
            <h4 class="mb-1 mt-0">Eventos</h4>
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
    <div style="height: 2px" class="bg-white rounded w-100 mb-4">

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-xl-12 mb-4">
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createEvent">Criar
                Evento</button>

            <!-- Modal -->
            
            <div id="createEvent" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crie o seu novo evento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nome do seu evento</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        aria-describedby="emailHelp" placeholder="Exemplo: Conferencia de kekeke">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="date">Date & Time</label>
                                    <input type="datetime-local" id="date" name="date" class="form-control"
                                        placeholder="Date and Time">
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
                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">{{$event->name}}</span>
                                <h2 class="mb-0">24</h2>
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
@endsection
