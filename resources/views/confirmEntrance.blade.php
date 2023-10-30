@extends('layouts.vertical')

@section("css")
  <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section("breadcrumb")
  @if(session('success'))
    <div class="alert alert-success">
      {{session('success')}}
    </div>
  @endif

  <div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb fs-1">
                <h4 class="breadcrumb-item text-muted fs-4">
                  Confirmar Presenca do ilustre {{$participant->name}} no evento {{$event->name}}!?
                </h4>
            </ol>
        </nav>
    </div>

    <div class="col-sm-8 col-xl-6">
      <div class="float-sm-right mt-3 mt-sm-0">
        <form 
          action="{{route("confirmEntranceUpdate", ["encryptedevent" => $encryptedevent, "encryptedparticipant" => $encryptedparticipant])}}"
          method="POST"
        >
          @csrf

          
          <button type="submit" class="btn btn-success" 
            @foreach ($event->participants as $participante)  
              @if($participant->id == $participante->id)            
                @if ($participante->pivot->status == "Presente")
                  {{'disabled'}}
                @else
                  Tsoka
                @endif
              @endif
            @endforeach
          >
            <i class='uil uil-check pr-2 mr-2 border-right '></i>Confirmar
          </button>
        </form>
      </div>
    </div>

    <div style="height: 2px" class="bg-white rounded w-100 my-4">
      
    </div>

  </div>
@endsection

@section('content')
  
@endsection