@extends('layouts.non-auth')



@section('content')  
  <div class="alert alert-success text-center">
      A sua presenca foi confirmada para o evento {{$event->name}}
    
  </div>

  <div class="row mt-4">
    <div class="col-12 col-md-4 mx-auto">
      <div href="/event/{{ $event->id }}" class="card position-relative">
        <div class="card-body p-0 z-10">
              <div class="media p-3">
                  <div class="media-body text-center d-flex fle">
                      <h2 class="mb-0">{{$event->name}}</h2>
                  </div>
                  <div class="">
                    <div class="d-flex justify-content-between">
                      <span>Localizacao</span>
                      <span>{{$event->banner_url}}</span>
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
@endsection 