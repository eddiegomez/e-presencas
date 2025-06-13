@extends("layouts.non-auth")

@section("content")
<div class="account-pages my-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-4 col-lg-5 col-8">
        <div class="text-center">
          <div>
            <img src="{{ asset('assets/images/not-found.png') }}" alt="Logo" height="" />
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 text-center">
        <h3 class="mt-3">Evento nao encontrado!</h3>
        <p class="text-muted mb-5">Contacte o Organizador para mais informações!
        </p>
        <a href="/" class="btn btn-lg btn-primary mt-4">Autenticação</a>
      </div>
    </div>
  </div>
  <!-- end container -->
</div>
<!-- end account-pages -->
@endsection