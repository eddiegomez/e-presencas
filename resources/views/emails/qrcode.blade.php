@component("mail::messages")
  {{-- {{ dd(asset($image_path)) }} --}}
  <img src="{{ asset("storage/qrcodes/" . $image_path) }}">
@endcomponent
