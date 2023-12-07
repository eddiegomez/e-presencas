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
          <h4 class="breadcrumb-item text-muted fs-4"><a href="{{ route("organizations.list") }}"
              class="text-muted">Instituicao</a></h4>
          <h4 class="breadcrumb-item active text-dark text-capitalize" aria-current="page">{{ $organization->name }}</h4>
        </ol>
      </nav>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createManagerModal">
          <i class='uil uil-upload-alt mr-1'></i>Criar Gestor
        </button>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>
@endsection

@hasrole("gestor do sistema")
  @section("content")
    <div class="row">
      <div class="col-12 col-md-8 col-lg-5">
        <h3>{{ $organization->name }}</h3>

        <p class="d-flex justify-content-between text-muted">
          <span>Email:</span>
          <span class="text-dark font-weight-bold">{{ $organization->email }}</span>
        </p>

        <p class="d-flex justify-content-between text-muted">
          <span>Phone:</span>
          <span class="text-dark font-weight-bold">{{ mb_substr($organization->phone, 0, 4) }}
            {{ substr($organization->phone, 4, 2) }}
            {{ substr($organization->phone, 6, 3) }} {{ substr($organization->phone, 9, 4) }}</span>
        </p>

        <p class="d-flex justify-content-between text-muted">
          <span>Location:</span>
          <span class="text-dark font-weight-bold">{{ $organization->location }}</span>
        </p>

        <p class="d-flex justify-content-between text-muted">
          <span>Website:</span>
          <a href="{{ route("organization.show", $organization->id) }}" class="text-dark font-weight-bold">Ver o
            webiste</a>
        </p>
      </div>
    </div>

    {{-- Divider line --}}
    <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>

    <div class="row">

      <div class="col-lg-8">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="header-title mt-0 mb-1">Gestores</h4>
            <p class="sub-header">
              Uma breve lista dos gestores da plataforma para esta organizacao.
            </p>

            <div class="table-responsive">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($managers as $index => $manager)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $manager->name }}</td>
                      <td>{{ $manager->email }}</td>
                      <td>{{ mb_substr($manager->phone, 0, 4) }} {{ substr($manager->phone, 4, 2) }}
                        {{ substr($manager->phone, 6, 3) }} {{ substr($manager->phone, 9, 4) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div id="createManagerModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
          {{-- Modal Content --}}
          <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
              <h5 class="modal-title text-capitalize" id="createManagerModalLabel">
                Criar um novo gestor
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            {{-- Modal Body --}}
            <div class="modal-body">
              <form action="{{ route("manager.store") }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Name --}}
                <div class="form-group">
                  <label for="name" class="col-lg-8 col-form-label">Nome</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Example: John Doe"
                    autocomplete="off" value="{{ old("name") }}" required>
                </div>
                @error("name")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

                {{-- Email --}}
                <div class="form-group">
                  <label for="email" class="col-lg-8 col-form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@gmail.com"
                    autocomplete="off" value="{{ old("email") }}" required>
                </div>
                @error("email")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

                {{-- Phone Number --}}
                <div class="form-group">
                  <label for="phone" class="col-lg-8 col-form-label">Numero de Telefone</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">+258</div>
                    </div>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="84 000 0000"
                      value="{{ old("phone") }}" required>
                  </div>
                </div>
                @error("phone")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

                {{-- Organizacao --}}
                <div class="form-group">
                  <input type="hidden" name="organization" value="{{ $organization->id }}">
                </div>
                @error("organization")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-success">Criar</button>

                </div>
              </form>
            </div>

            {{-- Modal Footer --}}
          </div>

        </div>
      </div>

    </div>
  @endsection
@endhasrole
