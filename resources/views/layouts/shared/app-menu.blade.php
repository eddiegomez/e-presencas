<ul class="metismenu" id="menu-bar">
  <li class="menu-title">Navigation</li>

  <li>
    <a href="/dashboard">
      <i data-feather="home"></i>
      <span class="badge badge-success float-right">1</span>
      <span> Dashboard </span>
    </a>
  </li>

  {{-- System manager menu --}}
  @hasrole("gestor do sistema")
    <li>
      <a href="{{ route("managers.list") }}">
        <i data-feather="layers"></i>
        <span> Organizations </span>
      </a>
    </li>

    <li>
      <a href="{{ route("managers.list") }}">
        <i data-feather="user"></i>
        <span> Gestores </span>
      </a>
    </li>
  @endhasrole

  {{-- Institution manager menu --}}
  @hasrole("")
    <li>
      <a href="/events">
        <i data-feather="calendar"></i>
        <span> Events </span>
      </a>
    </li>
  @endhasrole

  <li>
    <a href="/participants">
      <i data-feather="user"></i>
      <span> Participants </span>
    </a>
  </li>
  @if (Auth::user()->user_role == 1)
    <li>
      <a href="/protocolos">
        <i data-feather="user"></i>
        <span> Protocolos </span>
      </a>
    </li>
  @endif
  {{-- <li>
        <a href="/schedule">
            <i data-feather="layers"></i>
            <span> Programas </span>
        </a>
    </li> --}}
  {{-- <li>
        <a href="javascript: void(0);">
            <i data-feather="inbox"></i>
            <span> Email </span>
            <span class="menu-arrow"></span>
        </a>

        <ul class="nav-second-level" aria-expanded="false">
            <li>
                <a href="/apps/email/inbox">Inbox</a>
            </li>
            <li>
                <a href="/apps/email/read">Read</a>
            </li>
            <li>
                <a href="/apps/email/compose">Compose</a>
            </li>
        </ul>
    </li> --}}
</ul>
