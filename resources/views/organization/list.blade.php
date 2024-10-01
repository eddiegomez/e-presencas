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
      <h4 class="mb-1 mt-0">Instituição</h4>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createManagerModal">
          <i class='uil uil-upload-alt mr-1'></i>Criar Instituição
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
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Porcurar pelo nome">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="col-lg-4 col-md-6 col-12  mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Procurar pela organizacao">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Procurar pelo email">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      @if (!$organizations->isEmpty())
        <div class="list w-100 d-flex">
          @foreach ($organizations as $organization)
            <div class=" col-sm-6 col-12 col-lg-4 col-xl-3">
              <div
                class="p-3 shadow rounded-lg border border-light bg-white d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="text-dark" style="cursor: pointer;"
                    onClick='detailsModal({{ $organization->id }},"{{ $organization->name }}",@json($organization->email), @json($organization->phone), @json($organization->location), @json($organization->website))'>
                    {{ $organization->name }}</h4>
                  <span class="text-muted">{{ $organization->email }}</span>
                </div>
                <div>
                  <button type="button" class="btn btn-info font-size-11 p-1"
                    onClick='editModal({{ $organization->id }},"{{ $organization->name }}",@json($organization->email), @json($organization->phone), @json($organization->location), @json($organization->website))'>
                    <i class="uil uil-edit-alt"></i>
                  </button>

                  <button type="button" class="btn btn-danger font-size-11 p-1"
                    onClick='deleteModal("{{ $organization->name }}", @json($organization->id))'>
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


    {{-- Create Organization Modal --}}
    <div id="createManagerModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="createManagerModalLabel">
              Criar uma nova instituição
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("organization.store") }}" method="POST" enctype="multipart/form-data">
              @csrf

              {{-- Name --}}
              <div class="form-group">
                <label for="name" class="col-lg-8 col-form-label">Nome</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old("name") }}"
                  placeholder="SIGLA - Nome da instituição" autocomplete="off">
                @error("name")
                  <span class="text-danger">{{ $message }}</span>
                @enderror

              </div>

              {{-- Email --}}
              <div class="form-group">
                <label for="email" class="col-lg-8 col-form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old("email") }}"
                  placeholder="email@instituicao.gov.mz" autocomplete="off" required>
                @error("email")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

                {{-- Phone Number --}}
                <div class="form-group">
                  <label for="phone" class="col-lg-8 col-form-label">Numero de Telefone</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">+258</div>
                    </div>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="+258"
                      autocomplete="off" value="{{ old("phone") }}">
                  </div>
                  @error("phone")
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

              {{-- Location --}}
              <div class="form-group">
                <label for="location" class="col-lg-8 col-form-label">Location</label>
                <input type="text" class="form-control" name="location" id="location"
                  value="{{ old("location") }}" placeholder="Av./Rua, Nº"
                  autocomplete="off" required>
                @error("location")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Website --}}
              <div class="form-group">
                <label for="website" class="col-lg-8 col-form-label">Website</label>
                <input type="text" class="form-control" name="website" id="website" value="{{ old("website") }}"
                  placeholder="url" autocomplete="off" required>
                @error("website")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

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

    {{-- Edit Organization Modal --}}
    <div id="editOrganizationModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="editOrganizationModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <form action="{{ route("organization.update") }}" method="POST" enctype="multipart/form-data">
              @csrf

              {{-- ID do manager --}}
              <input type="hidden" name="id" id="EorganizationId">
              @error("id")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              {{-- Name --}}
              <div class="form-group">
                <label for="name" class="col-lg-8 col-form-label">Nome</label>
                <input type="text" class="form-control" name="name" id="Ename" placeholder="Example: INAGE"
                  autocomplete="off" required>
              </div>
              @error("name")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              {{-- Email --}}
              <div class="form-group">
                <label for="email" class="col-lg-8 col-form-label">Email</label>
                <input type="email" name="email" id="Eemail" class="form-control"
                  placeholder="inage@inage.gov.mz" autocomplete="off" required>
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

              {{-- Location --}}
              <div class="form-group">
                <label for="location" class="col-lg-8 col-form-label">Location</label>
                <input type="text" class="form-control" name="location" id="Elocation"
                  placeholder="Example: Av Vladimir Lenine esquina com 24 de Julho" autocomplete="off" required>
              </div>
              @error("location")
                <span class="text-danger">{{ $message }}</span>
              @enderror

              {{-- Website --}}
              <div class="form-group">
                <label for="location" class="col-lg-8 col-form-label">Website</label>
                <input type="text" class="form-control" name="website" id="Ewebsite"
                  placeholder="Example: www.google.com" autocomplete="off" required>
              </div>
              @error("website")
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

    {{-- Organization Details Modal --}}
    <div id="organizationDetailsModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="organizationDetailsModalLabel"></h5>
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
            {{-- Location --}}
            <div class="d-flex justify-content-between mb-2 font-size-14">
              <span class="text-muted">Location</span>
              <span class="text-dark font-weight-bold" id="dLocation"></span>
            </div>
            {{-- Website --}}
            <div class="d-flex justify-content-between mb-2 font-size-14">
              <span class="text-muted">Website</span>
              <a href="" class="text-dark font-weight-bold" id="dWebsite" target="_blank">Ver Website</a>
            </div>
          </div>
          {{-- Modal Footer --}}
          <div class="modal-footer">
            <a href="" class="btn btn-secondary mx-auto w-50" id="seeMore" target="_blank">Ver mais
              detalhes</a>
          </div>
        </div>

      </div>
    </div>

    {{-- Delete Organization Modal --}}
    <div id="deleteOrganizationModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        {{-- Modal Content --}}
        <div class="modal-content">
          {{-- Modal Header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="deleteOrganizationModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          {{-- Modal Body --}}
          <div class="modal-body">
            <p>Tem certeza que deseja eliminar <span class="text-bold"></span> da lista de gestores?</p>
          </div>
          {{-- Modal Footer --}}
          <div class="modal-footer">
            <form action="{{ route("organization.destroy") }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id" id="organizationId">
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
      function detailsModal(id, name, email, phone, location, website) {
        document.getElementById("organizationDetailsModalLabel").innerHTML = name;
        document.getElementById("dEmail").innerHTML = email;
        document.getElementById("dPhone").innerHTML = phone;
        document.getElementById("dLocation").innerHTML = location;
        document.getElementById("dWebsite").href = website;
        document.getElementById("seeMore").href = `{{ route("organization.show", $organization->id) }}`;
        $("#organizationDetailsModal").modal('show');
      }

      function editModal(id, name, email, phone, location, website) {
        document.getElementById("editOrganizationModalLabel").innerHTML = "Editar gestor de nome " + name;
        document.getElementById("EorganizationId").value = id;
        document.getElementById("Ename").value = name;
        document.getElementById("Eemail").value = email;
        document.getElementById("Ephone").value = phone.slice(4);
        document.getElementById("Elocation").value = location;
        document.getElementById("Ewebsite").value = website;
        $("#editOrganizationModal").modal('show');
      }

      function deleteModal(name, organizationId) {
        document.getElementById("deleteOrganizationModalLabel").innerHTML = "Eliminar " + name;
        document.getElementById("organizationId").value = organizationId;
        $("#deleteOrganizationModal").modal("show");
      }
    </script>

    {{-- <script>
      $(document).ready(function() {
        $('#searchForm').submit(function(e) {
          e.preventDefault();

          var search = $('input[name="search"]').val();

          $.ajax({
            type: "GET",
            url: "{{ route("managers.list") }}",
            data: {
              search: search
            },
            sucess: function(gestores) {
              $('.list').html(gestores);
            }
          });
        });
      });
    </script> --}}
  @endsection
@endhasrole
