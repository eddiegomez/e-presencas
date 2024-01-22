  <div class="text-center">
    {{-- <style>
      .btn-accept {
        background: #5ad941;
        padding: .5rem;
        color: #fff;
        text-decoration: none;
      }
    </style>

    <p>Viemos por este meio convidar o ilustre <strong>{{ $participant->name }}</strong> para o evento
      <strong>{{ $event->name }}.</strong>
    </p>
    <p>
      O evento irá decorrer pelas <strong> {{ date("H:i", strtotime($event->start_time)) }}</strong> até às
      {{ date("H:i", strtotime($event->end_time)) }} do dia
      {{ $event->start_date }}.
    </p>

    <p>Clique o botão abaixo para confirmar a sua presença!</p>
    <div>
      <a style="padding: 1rem; background-color: #5ad941; color: #fff"
        href="{{ route("invite.acceptInvite", ["encryptedevent" => base64_encode($event->id), "encryptedparticipant" => base64_encode($participant->id)]) }}">Confirme
        a sua presenca
      </a>

      <a class="btn-accept" style="padding: 1rem; background-color: #eee; color: #fff;"
        href="{{ route("invite.acceptInvite", ["encryptedevent" => base64_encode($event->id), "encryptedparticipant" => base64_encode($participant->id)]) }}">Rejeitar
        o Convite
      </a>
    </div>

    <p>O seu Convite é o seguinte! Exiba na porta para ter acesso ao evento.</p> --}}
    <img src="{{ asset("storage/qrcodes/" . $image_path . ".png") }}" />
  </div>
