@extends("layouts.attend")

@section("content")
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="text-center mb-5">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="50px" />
            <h3 class="mt-3">ePresenças</h3>
            <p>Desenvolvido pelo Instituto Nacional de Governo Electrónico (INAGE, IP)</p>
        </div>

        <div class="justify-content-center">
            <center>
                <div class="col-md-12">
                    @if (session('message'))
                    <div class="d-flex justify-content-center align-items-center w-100 mb-3" style="min-height: 200px;">
                        <img src="{{ asset('assets/gifs/success.gif') }}" alt="Confirmado" class="img-fluid" style="max-width: 80%; height: auto;" />
                    </div>
                    <div class="alert alert-info text-center d-flex align-items-center justify-content-center gap-2">
                        <span>{{ session('message') }}</span>
                    </div>
                    @else
                    <form action="{{ route('entrance', ['encryptedevent' => $event->hash]) }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="contacto">Insira seu email ou número de telefone</label>
                            <input type="text" name="contacto" id="contacto" class="form-control" required placeholder="ex: exemplo@email.com ou 84xxxxxxx">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Confirmar Presença</button>
                    </form>
                    @endif
                </div>
            </center>
        </div>
    </div>
</div>
@endsection