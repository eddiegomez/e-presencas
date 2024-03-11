@extends("layouts.non-auth");

@section("content")
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-12 col-md-6 bg-white p-4 rounded-lg">
        <form action="{{ route("protocolo.confirmRegister", base64_encode($user->id)) }}" method="POST">
          @csrf
          <h3 class="text-center mb-4">Confirmar o seu Registo</h3>
          {{-- Input for the Name --}}
          <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
              style="cursor: not-allowed" value="{{ $user->name }}" disabled>
          </div>
          {{-- Input for the Email  --}}
          <div class="form-group">
            <label for="name">Email</label>
            <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp"
              style="cursor: not-allowed" value="{{ $user->email }}" disabled>
          </div>
          {{-- Input for the  Default Password  --}}
          <div class="form-group">
            <label for="defaultPassword">Password Padrao</label>
            <input type="password" class="form-control" id="defaultPassword" name="defaultPassword"
              aria-describedby="passwordHelp">
          </div>
          {{-- Input for the  Password  --}}
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp">
          </div>
          {{-- Input for the  Confirmation Password  --}}
          <div class="form-group">
            <label for="password_confirmation">Confirmar Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
              aria-describedby="passwordHelp">
          </div>

          <button type="submit" class="btn btn-primary w-100">Confirmar Registro</button>
        </form>
      </div>
    </div>
  </div>
@endsection
