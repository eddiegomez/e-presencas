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
      <h4 class="mb-1 mt-0">Administradores</h4>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createAdminModal">
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

      <div class="col-4 mb-4">
        <div class="input-group">
          <input type="text" class="form-control search-input mr-2" placeholder="Porcurar pelo nome" id="searchInput">
        </div>
      </div>

      <div class="col-md-4 col-12  mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Procurar pela organizacao">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="col-4 mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Procurar pelo email">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      @if (!$admins->isEmpty())
        <div class="list w-100 d-flex" id="gestoresContainer">
          @foreach ($admins as $admin)
            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
              <div
                class="p-3 shadow rounded-lg border border-light bg-white d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="text-dark text-capitalize" style="cursor: pointer;"
                    onClick='detailsModal("{{ $admin->name }}",@json($admin->email), @json($admin->phone), @json($admin->organization->name))'>
                    {{ $admin->name }}</h4>
                  <span class="text-muted">{{ $admin->organization->name }}</span>
                </div>
                <div>
                  <button type="button" class="btn btn-info font-size-11 p-1"
                    onClick='editModal({{ $admin->id }},"{{ $admin->name }}",@json($admin->email), @json($admin->phone), @json($admin->organization->id))'>
                    <i class="uil uil-edit-alt"></i>
                  </button>

                  <button type="button" class="btn btn-danger font-size-11 p-1"
                    onClick='deleteModal("{{ $admin->name }}", @json($admin->id))'>
                    <i class="uil uil-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="col-12">
          <div class="alert alert-warning text-center">
            <h6 class="text-white">Ainda nao tem nenhum gestor registado!</h6>
          </div>
        </div>
      @endif
    </div>


    {{-- Create Manager Modal --}}
    <div id="createAdminModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="createAdminModalLabel">
              Criar um novo Administradir
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("admin.store") }}" method="POST" enctype="multipart/form-data">
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

    {{-- Edit Manager Modal --}}
    <div id="editAdminModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="editAdminModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("admin.update") }}" method="POST" enctype="multipart/form-data">
              @csrf

              {{-- ID do manager --}}
              <input type="hidden" name="id" id="EmanagerId">
              @error("id")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              {{-- Name --}}
              <div class="form-group">
                <label for="name" class="col-lg-8 col-form-label">Nome</label>
                <input type="text" class="form-control" name="name" id="Ename"
                  placeholder="Example: John Doe" autocomplete="off">
              </div>
              @error("name")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              {{-- Email --}}
              <div class="form-group">
                <label for="email" class="col-lg-8 col-form-label">Email</label>
                <input type="email" name="email" id="Eemail" class="form-control"
                  placeholder="john.doe@gmail.com" autocomplete="off">
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
                  <input type="text" class="form-control" id="Ephone" name="phone" placeholder="84 000 0000">
                </div>
              </div>
              @error("phone")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Editar</button>

              </div>
            </form>
          </div>

          {{-- Modal Footer --}}
        </div>

      </div>
    </div>

    {{-- Admin Details Modal --}}
    <div id="AdminDetailsModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="AdminDetailsModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            {{-- Email --}}
            <div class="d-flex justify-content-between mb-2 font-size-14">
              <span class="text-muted">Email</span>
              <span class="text-dark font-weight-bold" id="dEmail"></span>
            </div>
            {{-- Phone --}}
            <div class="d-flex justify-content-between mb-2 font-size-14">
              <span class="text-muted">Phone</span>
              <span class="text-dark font-weight-bold" id="dPhone"></span>
            </div>
            {{-- Organization --}}
            <div class="d-flex justify-content-between mb-2 font-size-14">
              <span class="text-muted">Organization</span>
              <span class="text-dark font-weight-bold" id="dOrganization"></span>
            </div>
          </div>
          {{-- Modal Footer --}}
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary mx-auto w-50" data-dismiss="modal">Fechar</button>
          </div>
        </div>

      </div>
    </div>

    {{-- Delete Admin Modal --}}
    <div id="deleteAdminModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="deleteAdminModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <p>Tem certeza que deseja eliminar <span class="font-weight-bold" id="dname"></span> da lista de
              administradores?
            </p>
          </div>
          {{-- Modal Footer --}}
          <div class="modal-footer">
            <form action="{{ route("admin.destroy") }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id" id="managerId">
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      function detailsModal(name, email, phone, organization) {
        document.getElementById("AdminDetailsModalLabel").innerHTML = name;
        document.getElementById("dEmail").innerHTML = email;
        document.getElementById("dPhone").innerHTML = phone;
        document.getElementById("dOrganization").innerHTML = organization;
        $("#AdminDetailsModal").modal('show');
      }

      function editModal(id, name, email, phone, organization) {
        document.getElementById("editAdminModalLabel").innerHTML = "Editar Administrador de nome " + name;
        document.getElementById("EmanagerId").value = id;
        document.getElementById("Ename").value = name;
        document.getElementById("Eemail").value = email;
        document.getElementById("Ephone").value = phone;
        $("#editAdminModal").modal('show');
      }

      function deleteModal(name, managerId) {
        document.getElementById("deleteAdminModalLabel").innerHTML = "Eliminar " + name;
        document.getElementById("dname").innerHTML = name;
        document.getElementById("managerId").value = managerId;
        $("#deleteAdminModal").modal("show");
      }
    </script>
  @endsection
@endhasrole
