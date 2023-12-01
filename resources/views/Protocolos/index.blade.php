@extends("layouts.vertical")

@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  @if (session("success"))
    <div class="alert alert-success">
      {{ session("success") }}
    </div>
  @endif

  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <h4 class="mb-1 mt-0">Protocolos</h4>
    </div>

    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createStaff">
          <i class='uil uil-plus mr-1'></i>Adicionar Protocolo
        </button>
      </div>
    </div>
  </div>

  {{-- Divider line --}}
  <div style="height: 2px" class="bg-white dark:bg-white rounded w-100 mb-4"></div>

  {{-- Modal --}}

  <div id="createStaff" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="exampleModalLabel">Editar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>

        <div class="modal-body">
          <form action="{{ route("protocolo.store") }}" method="POST">
            @csrf

            {{-- Name Input --}}
            <div class="form-group">
              <label for="name">Nome do Protocolo</label>
              <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                placeholder="Exemplo: Conferencia de kekeke">
            </div>

            {{-- email Input --}}
            <div class="form-group">
              <label for="email">Email do Protocolo</label>
              <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                placeholder="Exemplo: John.doe@inage.gov.mz">
            </div>

            {{-- password Input --}}
            {{-- <div class="form-group">
              <label for="password">Password do Protocolo</label>
              <input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp"
                placeholder="Exemplo: John.doe@inage.gov.mz">
            </div> --}}


            {{-- <div class="form-group mb-3">
              <label for="date">Data</label>
              <input type="date" id="date" name="date" class="form-control" placeholder="Date and Time">
            </div> --}}

            {{-- <div class="form-group mb-3">
              <label for="banner">Default file
                input
              </label>
              <div class="col-lg-12">
                <input type="file" accept="image/*" class="form-control" id="banner" name="banner">
              </div>
            </div> --}}

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" type="submit">Criar Protocolo</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection

@section("content")
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          {{-- <a href="" class="btn btn-primary btn-sm float-right">
            <i class='uil uil-export ml-1'></i> Export
          </a> --}}
          <h5 class="card-title mt-0 mb-0 header-title">Protocolos</h5>

          <div class="table-responsive mt-4">
            <table class="table table-hover table-nowrap mb-0">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nome</th>
                  <th scope="col">Email</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-right">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($protocolos as $protocolo)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $protocolo->name }}</td>
                    <td>{{ $protocolo->email }}</td>
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
                        onclick='showEditModal({{ $protocolo->id }},@json($protocolo->name),@json($protocolo->email))'>
                        <i class='uil uil-pen font-size-11'></i>
                      </a>
                      <a href="#" class="btn btn-danger"
                        onclick='showDeleteModal({{ $protocolo->id }},@json($protocolo->name))'>
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

  <div id="deleteStaff" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="user">Remover o protocolo <strong id="pNome"></strong>?
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">

          <p>Tem certeza que pretende remover o protocolo <strong id="rmNome"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <form id="removeStaff" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="protocoloId" name="protocoloId" value="" />
            <button type="submit" class="btn btn-danger">Confirmar remoção</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="editStaff" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="user">Editar informacoes do protocolo <strong id="ENome"></strong>?
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <form id="editStaffForm" method="POST">
            @csrf
            <input type="hidden" id="Eid" name="Eid" value="" />

            <div class="form-group row">
              <label for="name" class="col-lg-8 col-form-label">Nome do Protocolo </label>
              <input type="text" name="Ename" id="Ename" class="form-control" value=""
                placeholder="John Xibadula">
            </div>

            <div class="form-group row">
              <label for="email" class="col-lg-8 col-form-label">Email do protocolo </label>
              <input type="text" name="Eemail" id="Eemail" class="form-control" value=""
                placeholder="xibadula@inage.gov.mz">
            </div>


        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Confirmar edição</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>

  <script>
    function showDeleteModal(protocoloId, name) {
      // Get a reference to the form element
      var form = document.getElementById('removeStaff');

      // Set the action attribute to the desired URL
      form.action = "{{ route("protocolo.delete", $protocolo->id) }}"; // Replace with your desired URL

      document.getElementById('protocoloId').value = protocoloId;
      document.getElementById('rmNome').innerHTML = name;
      document.getElementById('pNome').innerHTML = name;
      $("#deleteStaff").modal('show');
    }

    function showEditModal(protocoloId, name, email) {
      // Get a reference to the form element
      var form = document.getElementById('editStaffForm');

      // Set the action attribute to the desired URL
      form.action = "{{ route("protocolo.edit") }}"; // Replace with your desired URL

      document.getElementById('Eid').value = protocoloId;
      document.getElementById('ENome').innerHTML = name;
      document.getElementById('Ename').value = name;
      document.getElementById('Eemail').value = email;
      $("#editStaff").modal('show');
    }
  </script>
@endsection
