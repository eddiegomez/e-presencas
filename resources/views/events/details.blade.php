@extends("layouts.event_details")

@section("content")
<div class="container register">
    <div class="row">
        <div class="col-md-3 col-12 register-left">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="" />

            <h3>ePresenças</h3>
            <p>Desenvolvido pelo Instituto Nacional de Governo Electrónico (INAGE,IP)</p>
            <a href="/"><input type="submit" value="Autenticar-se" /></a><br />
        </div>

        <div class="col-md-9 col-12 register-right">
            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Detalhes do Evento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Registar-se</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
                @endif

                <!-- Detalhes do evento -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h3 class="register-heading">{{ $event->name }}</h3>

                    <div class="row register-form" id="event-details">
                        <div class="col-12 col-md-8 mb-2 d-flex align-items-center">
                            <i class="bi bi-house-fill mr-2"></i>
                            <span>{{ $event->organizer }}</span>
                        </div>
                        <div class="col-12 col-md-4 mb-2 d-flex align-items-center">
                            <i class="bi bi-calendar-event-fill mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-12 col-md-8 mb-2 d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill mr-2"></i>
                            <a href="{{ $event->address_url }}" target="_blank"><span>{{ $event->address }}</span></a>
                        </div>
                        <div class="col-12 col-md-4 mb-2 d-flex align-items-center">
                            <i class="bi bi-clock-fill mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</span>
                        </div>

                        <div class="col-12 mb-2 d-flex align-items-start">
                            <!--!<i class="bi bi-list-check mr-2 mt-1"></i>-->
                            @if ($schedules->count() == 0)
                            <span class="text-danger font-size-14">Nenhum programa anexado a este evento!</span>
                            @endif

                            @foreach ($schedules as $schedule)

                            <a href="{{ asset('storage/schedules/' . $schedule->pdf_url) }}" target="_blank" class="d-flex align-items-center text-decoration-none mb-2 ml-1">
                                <i class="bi bi-file-earmark-pdf-fill mr-2"></i>
                                <span class="mt-1">{!! nl2br(e($schedule->name)) !!}</span>
                            </a>

                            @endforeach
                        </div>
                        <div class="col-12 mb-2 d-flex align-items-start">
                            <i class="bi bi-chat-quote-fill mr-2 mt-1"></i>
                            <span>{!! nl2br(e($event->description)) !!}</span>
                        </div>
                        <div class="col-12">
                            <center>
                                <img src="{{ asset('storage/'. $event->banner_url) }}" alt="Logo" height="150px" />
                            </center>
                        </div>
                    </div>

                </div>

                <!-- Form de registo -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <h3 class="register-heading">{{ $event->name }}</h3>

                    <form action="{{ route('participants.register') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="row register-form">
                            <div class="row col-md-12">

                                <div class="form-group mb-3 col-md-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nome *" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Apelido *" value="{{ old('last_name') }}" required>
                                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <input type="text" maxlength="10" minlength="10" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Telefone *" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <input type="text" class="form-control @error('degree') is-invalid @enderror" name="degree" placeholder="Ocupação *" value="{{ old('degree') }}" required>
                                    @error('degree')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group mb-3 col-md-6">
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Informação adicional" rows="3">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <input type="submit" class="btnRegister btn btn-primary col-md-4" style="float: right;" value="Registar" />
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection