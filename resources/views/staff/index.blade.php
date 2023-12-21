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
      <h4 class="mb-1 mt-0">Protocolos</h4>
    </div>
    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createStaffModal">
          <i class='uil uil-plus mr-1'></i>Criar Protocolos
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
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            {{-- <a href="" class="btn btn-primary btn-sm float-right">
            <i class='uil uil-export ml-1'></i> Export
          </a> --}}
            <h5 class="card-title mt-0 mb-0 header-title">Lista de Protocolos</h5>

            <div class="table-responsive mt-4">
              <table class="table table-hover table-nowrap mb-0">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Status da Conta</th>
                    <th scope="col" class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($protocolos as $protocolo)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $protocolo->name }}</td>
                      <td>{{ $protocolo->email }}</td>
                      <td>+258 {{ mb_substr($protocolo->phone, 0, 2) }}
                        {{ substr($protocolo->phone, 2, 3) }}
                        {{ substr($protocolo->phone, 5, 4) }} {{ substr($protocolo->phone, 9, 4) }}</td>
                      <td>
                        @if (!$protocolo->email_verified_at)
                          <span class="badge py-1 badge-soft-warning">
                            Em espera
                          </span>
                        @else
                          <span class="badge py-1 badge-soft-success">
                            Activo
                          </span>
                        @endif
                      </td>

                      <td class="text-right">
                        <a href="#" class="btn btn-primary"
                          onclick='editModal({{ $protocolo->id }}, @json($protocolo->name), @json($protocolo->email), @json($protocolo->phone))''>
                          <i class='uil uil-pen font-size-11'></i>
                        </a>
                        <a href="#" class="btn btn-danger"
                          onclick='deleteStaff({{ $protocolo->id }}, @json($protocolo->name))'>
                          <i class='uil uil-trash-alt font-size-11'></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div> <!-- end table-responsive-->
          </div> <!-- end card-body-->
        </div> <!-- end card-->
      </div>
    </div>

    {{-- Create Staff Modal --}}
    <div id="createStaffModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          {{-- Modal header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="createStaffModallLabel">Dados do protocolo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>

          {{-- Modal body --}}
          <div class="modal-body">
            <form action="{{ route("staff.store") }}" method="POST">
              @csrf

              {{-- Name Input --}}
              <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                  placeholder="Exemplo: Martinha">
                @error("name")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Email Input --}}
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                  placeholder="Exemplo: John.doe@inage.gov.mz">
                @error("email")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Phone Number --}}
              <div class="form-group">
                <label for="phone">Numero de Telefone</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+258</div>
                  </div>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="84 000 0000"
                    autocomplete="off" value="{{ old("phone") }}">
                </div>
                @error("phone")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Modal footer --}}
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" type="submit">Editar Protocolo</button>
              </div>
            </form> {{-- End form --}}
          </div> {{-- End Modal body --}}
        </div> {{-- End Modal Content --}}
      </div> {{-- End Modal Dialog --}}
    </div> {{-- End Modal Content --}}

    {{-- Details Staff Modal --}}
    <div id="editStaffModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          {{-- Modal header --}}
          <div class="modal-header">
            <h5 class="modal-title text-capitalize" id="createStaffModallLabel">Dados do protocolo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>

          {{-- Modal body --}}
          <div class="modal-body">
            <form action="{{ route("staff.update") }}" method="POST">
              @csrf

              {{-- ID Input --}}
              <input type="hidden" name="id" id="editId">

              {{-- Name Input --}}
              <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="Ename" name="name" aria-describedby="emailHelp"
                  placeholder="Exemplo: Martinha">
                @error("name")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Email Input --}}
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="Eemail" name="email"
                  placeholder="Exemplo: John.doe@inage.gov.mz">
                @error("email")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Phone Number --}}
              <div class="form-group">
                <label for="phone">Numero de Telefone</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+258</div>
                  </div>
                  <input type="text" class="form-control" id="Ephone" name="phone" placeholder="84 000 0000"
                    autocomplete="off" value="{{ old("phone") }}">
                </div>
                @error("phone")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              {{-- Modal footer --}}
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" type="submit">Criar Protocolo</button>
              </div>
            </form> {{-- End form --}}
          </div> {{-- End Modal body --}}
        </div> {{-- End Modal Content --}}
      </div> {{-- End Modal Dialog --}}
    </div>

    {{-- Delete Event Modal --}}
    <div id="deleteStaffModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="user">Remover o <strong id="sName"></strong> dos protocolos?
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          </div>
          <div class="modal-body">

            <p>Tem certeza que pretende remover o participante <strong id="rmName"></strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <form id="removeParticipant" method="POST" action="{{ route("staff.delete") }}">
              <input type="hidden" name="_method" value="Delete">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" id="deleteId" name="id" />

              <button type="submit" class="btn btn-danger">Confirmar remoção</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      function editModal(id, name, email, phone) {
        document.getElementById('editId').value = id;
        document.getElementById('Ename').value = name;
        document.getElementById('Eemail').value = email;
        document.getElementById('Ephone').value = phone;

        $("#editStaffModal").modal("show");
      }

      function deleteStaff(id, name) {
        document.getElementById('deleteId').value = id;
        document.getElementById('sName').innerHTML = name;
        document.getElementById('rmName').innerHTML = name
        $("#deleteStaffModal").modal("show");
      }
    </script>
  @endsection
@endhasrole
