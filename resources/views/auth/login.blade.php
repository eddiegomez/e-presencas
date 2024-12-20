@extends("layouts.non-auth")

@section("content")
<div class="account-pages my-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10">
        <div class="card">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-md-6 p-5">
                <div class="mx-auto mb-5 text-center">
                  <a href="/">
                    <img src="assets/images/logo.png" alt="" height="80" />
                    {{-- <h3 class="d-inline align-middle ml-1 text-logo">Shreyu</h3> --}}
                  </a>
                </div>

                <h6 class="h5 mb-0 mt-4">Seja bem vindo!</h6>
                <p class="text-muted mt-1 mb-4">Insira as suas credenciais para ter acesso ao seu perfil.</p>

                @if (session("error"))
                <div class="alert alert-danger">{{ session("error") }}</div>
                <br>
                @endif
                @if (session("success"))
                <div class="alert alert-success">{{ session("success") }}</div>
                <br>
                @endif

                <form action="{{ route("login") }}" method="post" class="authentication-form">
                  @csrf

                  <div class="form-group">
                    <label class="form-control-label">Email</label>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="icon-dual" data-feather="mail"></i>
                        </span>
                      </div>
                      <input type="email" class="form-control @if ($errors->has(" email")) is-invalid @endif"
                        id="email" placeholder="email@dominio.gov.mz" name="email" value="{{ old("email") }}" />

                      @if ($errors->has("email"))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first("email") }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group mt-4">
                    <label class="form-control-label">Palavra passe</label>
                    <a href="{{ route("password.request") }}"
                      class="float-right text-muted text-unline-dashed ml-1">Esqueceu a password?</a>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="icon-dual" data-feather="lock"></i>
                        </span>
                      </div>
                      <input type="password" class="form-control @if ($errors->has(" password")) is-invalid @endif"
                        id="password" placeholder="Insira a sua palavra-passe" name="password" />

                      @if ($errors->has("password"))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first("password") }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group mb-4">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="checkbox-signin"
                        {{ old("remember") ? "checked" : "" }} />
                      <label class="custom-control-label" for="checkbox-signin">Lembrar Palavra-passe?</label>
                    </div>
                  </div>

                  <div class="form-group mb-0 text-center">
                    <button class="btn btn-primary btn-block" type="submit"> Log In
                    </button>
                  </div>
                </form>

                <div class="form-group mt-3">
                  <a href="#" data-toggle="modal" data-target="#qrModal">
                    <label class="form-control-label">Gerar código QR</label>
                  </a>
                  <i class="icon-dual" data-feather="maximize"></i>
                </div>

                <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h5 class="modal-title" id="qrModalLabel">Gerar Código QR</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <!-- Modal Body -->
                      <div class="modal-body text-center"> <!-- Added text-center for horizontal centering -->
                        <form id="qrForm">
                          <!-- Input Field for URL -->
                          <div class="form-group">
                            <label for="qrUrl">Insira a URL:</label>
                            <input type="text" class="form-control" id="qrUrl" placeholder="Digite a URL aqui">
                          </div>
                          <!-- Button to Generate QR Code -->
                          <button type="button" class="btn btn-primary" id="generateQR">Gerar QR Code</button>
                        </form>
                        <!-- QR Code Display Area -->
                        <div class="mt-3 d-flex justify-content-center" id="qrCodeContainer"></div>
                        <!-- Download Button -->
                        <div class="mt-3">
                          <a id="downloadQR" class="btn btn-success d-none" download="qrcode.png">Baixar Código QR</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- <div class="py-3 text-center"><span class="font-size-16 font-weight-bold">Or</span>
                                                                                                      </div>
                                                                                                      <div class="row">
                                                                                                          <div class="col-6">
                                                                                                              <a href="" class="btn btn-white"><i
                                                                                                                      class='uil uil-google icon-google mr-2'></i>With Google</a>
                                                                                                          </div>
                                                                                                          <div class="col-6 text-right">
                                                                                                              <a href="" class="btn btn-white"><i
                                                                                                                      class='uil uil-facebook mr-2 icon-fb'></i>With Facebook</a>
                                                                                                          </div>
                                                                                                      </div> -->
              </div>
              <div class="col-lg-6 d-none d-md-inline-block">
                <div class="auth-page-sidebar" style="background-image: url({{ asset("assets/images/auth-bg.jpg") }})">
                  <div class="overlay"></div>
                  <div class="auth-user-testimonial">
                    <p class="font-size-24 font-weight-bold text-white mb-1">Individualmente somos uma gota, juntos
                      somos um oceano!</p>
                    {{-- <p class="lead">"It's a elegent templete. I love it very much!"</p> --}}
                    <!--<p>- Eng. Boa</p>-->
                  </div>
                </div>
              </div>
            </div>

          </div> <!-- end card-body -->
        </div>
        <!-- end card -->

        {{-- <div class="row mt-3">
            <div class="col-12 text-center">
              <p class="text-muted">Nao tem conta?
                {{-- <a href="/register" class="text-primary font-weight-bold ml-1">Sign Up
                </a> --}}
        {{-- </p> --}}
        {{-- </div> <!-- end col --> --}}
        {{-- </div> --}}
        <!-- end row -->

      </div> <!-- end col -->
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
</div>
<!-- end page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateQR');
    const qrInput = document.getElementById('qrUrl');
    const qrContainer = document.getElementById('qrCodeContainer');
    const downloadBtn = document.getElementById('downloadQR');

    generateBtn.addEventListener('click', function() {
      // Clear any existing QR Code
      qrContainer.innerHTML = '';
      downloadBtn.classList.add('d-none');

      const url = qrInput.value.trim();

      if (url === '') {
        alert('Por favor, insira uma URL válida.');
        return;
      }

      // Generate QR Code
      const qrCode = new QRCode(qrContainer, {
        text: url,
        width: 200,
        height: 200,
      });

      // Wait for QR code generation, then convert to image
      setTimeout(() => {
        const qrCanvas = qrContainer.querySelector('canvas');
        if (qrCanvas) {
          const qrImage = qrCanvas.toDataURL('image/png');
          downloadBtn.href = qrImage; // Set the download link
          downloadBtn.classList.remove('d-none'); // Show the download button
        }
      }, 500); // Delay to ensure QR code is rendered
    });
  });
</script>

@endsection