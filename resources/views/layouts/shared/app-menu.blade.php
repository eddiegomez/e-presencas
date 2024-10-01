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
      <a href="{{ route("organizations.list") }}">
        <i data-feather="layers"></i>
        <span> Instituições </span>
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
  @hasrole("gestor")
    <li>
      <a href='{{ route("event.list") }}'>
        <i data-feather="calendar"></i>
        <span> Eventos </span>
      </a>
    </li>
    <li>
      <a href="/participants">
        <i data-feather="user"></i>
        <span> Participantes </span>
      </a>
    </li>
    <li>
      <a href="/protocolos">
        <i data-feather="user"></i>
        <span> Protocolos </span>
      </a>
    </li>
  @endhasrole
  {{-- <li>
        <a href="/schedule">
            <i data-feather="layers"></i>
            <span> Programas </span>
        </a>
    </li> --}}
</ul>
