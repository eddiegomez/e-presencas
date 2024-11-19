@extends("layouts.non-auth")
<style>
  .initials-placeholder {
    background-color: #808080;
    /* Grey background */
    color: white;
    /* White text color */
    width: 100px;
    /* Set a specific width */
    height: 100px;
    /* Set a specific height to match the width, making the shape a circle */
    border-radius: 50%;
    /* Make the shape circular */
    display: inline-block;
    /* Allows the use of text-align and line-height for centering */
    text-align: center;
    /* Center the text horizontally */
    line-height: 100px;
    /* Center the text vertically */
    top: 20px;
    position: relative;
  }

  .border-shadow {
    box-shadow: 0 1px 5px 0 rgba(82, 93, 102, .25), 0 2px 8px 0 rgba(82, 93, 102, .15);
  }


  /*Float Window*/
  body {
    font-family: Arial, sans-serif;
  }

  .floating-window {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: white;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    transform: translateY(100%);
    z-index: 1000;
  }

  .floating-window-content {
    padding: 20px;
  }

  .floating-window .close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
  }

  .success-message {
    color: green;
    background-color: #e8f5e9;
    border: 1px solid #c8e6c9;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    margin-bottom: 10px;
  }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
@section("content")
<center>
  <div class="col-md-12 col-xl-3 mb-4 mt-5">
    @auth
    @if(Auth::user()->roles->contains('id', 3))
    @if(sizeof($eventos) > 0)
    @if($invites->status === "Participou")
    <div id="" class="success-message">
      Presença marcada!
    </div>
    @else
    <div id="entrance-actions">
      <button type="button" class="btn btn-warning mt-3 mb-3 border-shadow" style="border-radius: 80px; font-weight: 700; font-size: 1.375rem; ">Cancelar</button>
      <button type="button" class="btn btn-success mt-3 mb-3 border-shadow" style="border-radius: 80px; font-weight: 700; font-size: 1.375rem;" onclick="marcarPresenca({{$eventos[0]->event_id}},{{$participant->id}},'Participou')">Confirmar</button>
    </div>
    @endif
    <div id="successMessage" class="success-message" style="display: none;">
    </div>
    @endif
    @endif
    @endauth
    <!-- Trigger the modal with a button -->
    <div class="card" style="border-radius: 20px; box-shadow: 0 1px 5px 0 rgba(82, 93, 102, .25), 0 2px 8px 0 rgba(82, 93, 102, .15);">
      <div class="card-body">

        <div class="text-center mt-2">
          @if ($participant->profile_url)
          {{-- Participant has a profile image --}}
          <img src="{{asset('storage/' . $participant->profile_url)}}" alt="" class="avatar-lg rounded-circle" style="width: 180px; height: 180px;">
          @else
          {{-- Participant does not have a profile image --}}
          @php
          // Get initials from participant's name
          $nameWords = explode(' ', $participant->name);
          $initials = '';
          foreach ($nameWords as $word) {
          $initials .= strtoupper(substr($word, 0, 1));
          }
          @endphp
          <div class="initials-placeholder" style="font-size: 44px">{{ $initials }}</div>
          @endif
          <h3 class="mt-5 mb-0" style="font-size: 2rem;font-weight: 700;line-height: 1.3;">{{$participant->name}} {{$participant->last_name}}</h3>
          <h6 class="text-muted fw-normal mt-2 mb-0" id="name" style="font-size: 1.5rem;font-weight: 600; line-height: 1.2; margin-bottom: .25rem;white-space: pre-wrap;">{{$participant->description}}</h6>
          <h6 class="text-muted fw-normal mt-2 mb-0" id="name" style="font-size: 1.5rem;font-weight: 600; line-height: 1.2; margin-bottom: .25rem;white-space: pre-wrap;">INAGE,IP - National eGovernment Institute</h6>
        </div>
        <input type="text" id="name" value="{{$participant->name}}" hidden>
        <input type="email" id="email" value="{{$participant->email}}" hidden>
        <input type="text" id="phone" value="{{$participant->phone_number}}" hidden>

        <!-- profile  -->
        <!--<div class="mt-4 pt-2 border-top">
        <h4 class="mb-3 fs-15">About</h4>
        <p class="text-muted mb-3">
          Hi I'm Shreyu. I am user experience and user interface designer.
          I have been working on UI &amp; UX since last 10 years.
        </p>
      </div>-->
        <div class="mt-3 pt-2 border-top">
          <div class="table-responsive">
            <table class="table table-borderless mb-0 text-muted">
              <tbody>
                <tr>
                  <th scope="row" style="padding: 1rem; padding-left: 0rem"><i data-feather="phone" style="color:#527a7a; font-weight: 700; size: 1.375rem;"></i></th>
                  <td style="padding: 0rem; padding-top: 0.75rem; padding-bottom: 0.75rem"><a href="tel:{{$participant->phone_number}}" style="font-weight: 600; color: #1f2e2e">{{$participant->phone_number}}</a></td>
                </tr>
                <tr>
                  <th scope="row" style="padding: 0.75rem; padding-left: 0rem"><i data-feather="mail"></i></th>
                  <td style="padding: 0rem; padding-top: 0.75rem; padding-bottom: 0.75rem"><a href="mailto:{{$participant->email}}" style="font-weight: 600; color: #1f2e2e">{{$participant->email}}</a></td>
                </tr>
                <tr>
                  <th scope="row" style="padding: 0.75rem; padding-left: 0rem"><i data-feather="home"></i></th>
                  <td style="padding: 0rem; padding-top: 0.75rem; padding-bottom: 0.75rem"><a href="{{$participant->website}}" style="font-weight: 600; color: #1f2e2e">{{$participant->nome_org}}</a></td>
                </tr>
                <tr>
                  <th scope="row" style="padding: 0.75rem; padding-left: 0rem"><i data-feather="globe"></i></th>
                  <td style="padding: 0rem; padding-top: 0.75rem; padding-bottom: 0.75rem"><a href="{{$participant->website}}" style="font-weight: 600; color: #1f2e2e">{{$participant->website}}</a></td>
                </tr>
              </tbody>
            </table>
          </div>
          <center><button type="button" class="btn btn-primary mt-3 border-shadow" style="border-radius: 80px; font-weight: 700; font-size: 1.375rem; background-color: #669999; border-color: #669999;" onclick="showFloatingWindow()">Gravar contacto</button>
          </center>

          <div id="floatingWindow" class="floating-window" style="border-top-left-radius: 30px; border-top-right-radius: 30px;">
            <div class="floating-window-content">
              <span class="close" onclick="closeFloatingWindow()">&times;</span>
              <a href="data:text/vcard;charset=utf-8,BEGIN:VCARD%0AVERSION:3.0%0AN:{{$participant->last_name}};{{$participant->name}};;;%0AFN:{{$participant->name}}%0AORG:{{$participant->nome_org}}%0ATITLE:{{$participant->degree}}%0ATEL:{{$participant->phone_number}}%0AEMAIL:{{$participant->email}}%0AURL:{{$participant->website}}%0AADR:;;%0ANOTE:{{$participant->description}}%0AADR:;;{{$participant->location}};%0AEND:VCARD"
                download="{{$participant->name}}{{$participant->last_name}}.vcf" class=" btn btn-primary mt-3 border-shadow" style="font-weight: 700; font-size: 1.375rem; background-color: #669999; border-color: #669999; width:100%; border-radius: 30px;">
                <i data-feather="user-plus"></i>
                <span> Guardar nos contactos </span>
              </a>

              <a href="#" class="btn mt-3 border-shadow" style="font-weight: 600; font-size: 1.375rem; width:100%" onclick="receiveByEmail()">
                <i data-feather="mail" style="color: #669999"></i>
                <span style="color: #669999;"> Receber por email </span>
              </a>
              <a href="#" onclick="downloadVCard()" class="btn mt-3 border-shadow" style="font-weight: 600; font-size: 1.375rem; width:100%">
                <i data-feather="download" style="color: #669999"></i>
                <span style="color: #669999;"> Baixar vCard </span>
              </a>
              <!-- Add your form or content here -->
            </div>
          </div>
        </div>
        <!--<div class="mt-2 pt-2 border-top">
        <h4 class="mb-3 fs-15">Skills</h4>
        <label class="badge badge-soft-primary">UI design</label>
        <label class="badge badge-soft-primary">UX</label>
        <label class="badge badge-soft-primary">Sketch</label>
        <label class="badge badge-soft-primary">Photoshop</label>
        <label class="badge badge-soft-primary">Frontend</label>
      </div>-->
      </div>
    </div>
  </div>
</center>

</div>

<script>
  function marcarPresenca(event_id, participant_id, status) {
    if (status === 'Participou') {
      $.ajax({
        url: '/staff/confirm/entrance/' + event_id + '/' + participant_id + '/' + status,
        type: 'PUT',
        data: {
          event_id: event_id,
          participant_id: participant_id,
          status: status
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          document.getElementById("entrance-actions").style.display = "none";
          $('#successMessage').text('Presença marcada!').fadeIn();
          console.log('Success:', response);
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });
    }
  }

  function downloadVCard() {
    const email = "{{$participant->email}}";
    const phone = "{{$participant->phone_number}}";
    const name = "{{$participant->name}}"; // assuming you have participant's name

    const vCard =
      `BEGIN:VCARD
VERSION:3.0
FN:${name}
TEL:${phone}
EMAIL:${email}
END:VCARD`;

    const blob = new Blob([vCard], {
      type: 'text/vcard'
    });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `${name.replace(/\s+/g, '_')}.vcf`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }

  function showFloatingWindow() {
    const floatingWindow = document.getElementById('floatingWindow');
    floatingWindow.style.display = 'block';
    setTimeout(() => {
      floatingWindow.style.transform = 'translateY(0)';
    }, 10);
  }

  function closeFloatingWindow() {
    const floatingWindow = document.getElementById('floatingWindow');
    floatingWindow.style.transform = 'translateY(100%)';
    setTimeout(() => {
      floatingWindow.style.display = 'none';
    }, 300);
  }

  function receiveByEmail() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const recipientEmail = prompt("Please enter your email address:");

    if (!recipientEmail) {
      alert("Email address is required!");
      return;
    }
    fetch('/send-email', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          name,
          email,
          phone,
          recipientEmail
        })
      })
      .then(response => response.json())
      .then(data => {
        window.alert(response);
        alert(data.message);
        closeFloatingWindow();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the email.');
      });
  }
</script>

@endsection