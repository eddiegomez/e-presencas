@extends("layouts.vertical")

@section("css")
  <link href="{{ URL::asset("assets/libs/flatpickr/flatpickr.min.css") }}" rel="stylesheet" type="text/css" />
@endsection

@section("content")
  <div class="row justify-content-center mt-4">
    <div class="col-12 col-md-8 text-center d-flex flex-column ">
      <h4 class="mb-3">Tem certeza que deseja retirar {{ $participant->name }} da lista dos participantes?</h4>
      <form action="{{ route("invite.destroy") }}" method="POST">
        @csrf
        <input type="hidden" name="event" value={{ $event->id }}>
        <input type="hidden" name="participant" value={{ $participant->id }}>
        <a href="{{ route("event", $event->id) }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </form>
    </div>
  </div>
@endsection
