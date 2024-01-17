@extends("layouts.non-auth")



@section("content")
  @if (isset($error))
    <div class="alert alert-danger text-center">
      {{ $error }}
    </div>
  @endif
  <div class="account-pages my-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-8">
          <div class="text-center">
            <div>
              <img src="/assets/images/not-found.png" alt="" class="img-fluid" />
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 text-center">
          <h3 class="mt-3">Não conseguimos encontrar a pagina!</h3>
          <p class="text-muted mb-5">Essa página não existe! <br /> Deve ter acontecido algum engano, contacte o gestor!
          </p>

          @hasanyrole("gestor do sistema|protocolo|gestor")
            <a href="/" class="btn btn-lg btn-primary mt-4">Voltar ao Dashboard</a>
          @endhasanyrole
        </div>
      </div>
    </div>
    <!-- end container -->
  </div>
  <!-- end account-pages -->
@endsection
