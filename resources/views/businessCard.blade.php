@extends("layouts.non-auth")
<style>
    .initials-placeholder {
        background-color: #808080; /* Grey background */
        color: white; /* White text color */
        width: 100px; /* Set a specific width */
        height: 100px; /* Set a specific height to match the width, making the shape a circle */
        border-radius: 50%; /* Make the shape circular */
        display: inline-block; /* Allows the use of text-align and line-height for centering */
        text-align: center; /* Center the text horizontally */
        line-height: 100px; /* Center the text vertically */
        top: 20px;
        position: relative;
    }
</style>

@section("content")
<center>
  <div class="col-md-12 col-xl-4 mb-4 mt-5">
    <!-- Trigger the modal with a button -->
    <div class="card">
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
          <div class="initials-placeholder">{{ $initials }}</div>
          @endif
          <h3 class="mt-5 mb-0">{{$participant->name}}</h3>
          <h6 class="text-muted fw-normal mt-2 mb-0">{{$participant->description}}</h6>

        </div>

        <!-- profile  -->
        <!--<div class="mt-4 pt-2 border-top">
        <h4 class="mb-3 fs-15">About</h4>
        <p class="text-muted mb-3">
          Hi I'm Shreyu. I am user experience and user interface designer.
          I have been working on UI &amp; UX since last 10 years.
        </p>
      </div>-->
        <div class="mt-3 pt-2 border-top">
          <h4 class="mb-2 fs-15">Informação de Contacto</h4>
          <div class="table-responsive">
            <table class="table table-borderless mb-0 text-muted">
              <tbody>
                <tr>
                  <th scope="row">Email</th>
                  <td>{{$participant->email}}</td>
                </tr>
                <tr>
                  <th scope="row">Phone</th>
                  <td>{{$participant->phone_number}}</td>
                </tr>
                <tr>
                  <th scope="row">Address</th>
                  <td>Address</td>
                </tr>
              </tbody>
            </table>
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
    // Select the element with the class 'initials-placeholder'
    var initialsPlaceholder = document.querySelector('.initials-placeholder');

    // Set the font size of the selected element
    initialsPlaceholder.style.fontSize = '44px'; // Example: Set font size to 24px
</script>
@endsection