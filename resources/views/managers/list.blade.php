@extends("layouts.vertical")


@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
      <h4 class="mb-1 mt-0">Gestores</h4>
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
      <div class="col-12 mb-4">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" class="form-control search-input mr-2" placeholder="Search...">
            <button class="btn btn-soft-primary input-group-text" type="button">
              <i class="uil uil-file-search-alt"></i>
            </button>
          </div>
        </form>
      </div>

      @if (!$gestores->isEmpty())
        <div class="list w-100">
          @foreach ($gestores as $gestor)
            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
              <div class="p-3 shadow-lg rounded-lg border border-light bg-white">
                <h4 class="text-dark">{{ $gestor->name }}</h4>
                <span class="text-muted">{{ $gestor->organization->name }}</span>
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
                  autocomplete="off">
              </div>

              {{-- Email --}}
              <div class="form-group">
                <label for="email" class="col-lg-8 col-form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@gmail.com"
                  autocomplete="off">
              </div>

              {{-- Phone Number --}}
              <div class="form-group">
                <label for="phone" class="col-lg-8 col-form-label">Numero de Telefone</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+258</div>
                  </div>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="84 000 0000">
                </div>
              </div>

              {{-- Organizacao --}}
              <div class="form-group">
                <label for="organization" class="col-lg-8 col-form-label">{{ __("Instituição") }}</label>
                <select type="text" name="organization" id="organization" class="form-control"
                  placeholder="john.doe@gmail.com" autocomplete="off">
                  <option value="" disabled selected>Escolha uma Instituicao</option>
                  @foreach ($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }} ({{ $organization->location }})
                    </option>
                  @endforeach

                </select>
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
    <script>
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
    </script>
  @endsection
@endhasrole
