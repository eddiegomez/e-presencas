  <div>
    <a
      href="{{ route("invite.acceptInvite", ["encryptedevent" => base64_encode($eventId), "encryptedparticipant" => base64_encode($participantId)]) }}">Confirme
      a sua presenca</a>
    <p>O seu Convite é o seguinte!</p>
    <img src="{{ asset("storage/qrcodes/" . $image_path . ".png") }}">
  </div>{{-- {{ dd(asset($image_path)) }} --}}
