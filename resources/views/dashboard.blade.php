@extends("layouts.vertical")


@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <h4 class="mb-1 mt-0">Dashboard</h4>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection

@section("content")
  @if (session("error"))
    <div class="alert alert-danger">
      {{ session("error") }}
    </div>
  @endif


  @hasrole("gestor do sistema")
    {{-- row --}}
    <div class="row">
      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">{{ __("Organizações") }}</span>
                <h2 class="mb-0">{{ $organizations }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Usuarios</span>
                <h2 class="mb-0">{{ $users }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Participantes</span>
                <h2 class="mb-0">
                  {{ $participants }}
                </h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Convites entregues</span>
                <h2 class="mb-0">{{ $events }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endhasrole
  <!-- row -->

  @hasrole("gestor")
    <div class="row">
      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Eventos</span>
                <h2 class="mb-0">{{ $events }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Participantes</span>
                <h2 class="mb-0">{{ $participants }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Protocolos</span>
                <h2 class="mb-0">
                  {{ 8 }}
                </h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Convites entregues</span>
                <h2 class="mb-0">{{ $invites }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Invites -->
    <div class="row">
      <div class="col-xl-8">
        <div class="card">
          <div class="card-body">
            {{-- <a href="" class="btn btn-primary btn-sm float-right">
              <i class='uil uil-export ml-1'></i> Export
            </a> --}}
            <h5 class="card-title mt-0 mb-0 header-title">Eventos & Participantes</h5>

            <div class="table-responsive mt-4">
              <table class="table table-hover table-nowrap mb-0">
                <thead>
                  <tr>
                    <th scope="col">QR</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Evento</th>
                    <th scope="col">Data</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                {{-- <tbody> --}}
                {{-- @foreach ($events as $event)
                    @foreach ($event->participants as $participant)
                      <tr>
                        <td>{{ $participant->id }}</td>
                        <td>{{ $participant->name }}</td>
                        <td class="text-capitalize">{{ $event->name }}</td>
                        <td>{{ $event->start_date }}</td>
                        <td>
                          <span
                            class="badge py-1
                              @if ($participant->pivot->status == "Presente") badge-soft-success 
                              @elseif ($participant->pivot->status == "Em espera") badge-soft-warning 
                              @elseif ($participant->pivot->status == "Confirmada") badge-soft-info 
                              @elseif ($participant->pivot->status == "Participou") badge-soft-success 
                              @elseif ($participant->pivot->status == "Rejeitada")badge-soft-warning 
                              @elseif ($participant->pivot->status == "Ausente") badge-soft-danger @endif
                            ">
                            {{ $participant->pivot->status }}
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  @endforeach
                </tbody> --}}
              </table>
            </div> <!-- end table-responsive-->
          </div> <!-- end card-body-->
        </div> <!-- end card-->
      </div> <!-- end col-->
    </div>
  @endhasrole

  @hasrole("protocolo")
    <div class="row">
      <div class="col-md-6 col-xl-3">
        <div class="card">
          <div class="card-body p-0">
            <div class="media p-3">
              <div class="media-body">
                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Eventos</span>
                <h2 class="mb-0">{{ $events }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endhasrole
@endsection

@section("script")
  <!-- optional plugins -->
  <script src="{{ URL::asset("assets/libs/moment/moment.min.js") }}"></script>
  <script src="{{ URL::asset("assets/libs/apexcharts/apexcharts.min.js") }}"></script>
  <script src="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.js") }}"></script>
@endsection

@section("script-bottom")
  <!-- init js -->
  <script src="{{ URL::asset("assets/js/pages/dashboard.init.js") }}"></script>
@endsection
