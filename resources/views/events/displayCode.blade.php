@extends("layouts.attend")

@section("content")
<div class="container my-5">
    <div class="text-center mb-5">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="50px" />
        <h3 class="mt-3">ePresenças</h3>
        <p>Desenvolvido pelo Instituto Nacional de Governo Electrónico (INAGE, IP)</p>
    </div>


    <div class="timeline">

        <!-- Passo 1 - Esquerda -->
        <div class="timeline-step left">
            <div class="timeline-marker">1</div>
            <div class="timeline-content">
                <h5>Abra o leitor de código</h5>
                <p>Usando seu celular, abra o leitor de código. Caso não possua algum instalado, baixe um de sua preferência para que possa ler o código.</p>
            </div>
        </div>
        <center>
            <!-- Passo 2 - Direita com QR code -->
            <div class="timeline-step right ml-5">
                <div class="timeline-marker qrcode-marker mr-3">
                    <img
                        id="qrcode"
                        onclick="displayQRCode(1)"
                        src="data:image/png;base64,{{ base64_encode(QrCode::color(0, 0, 0)->style('round')->eye('circle')->size(300)->format('png')->generate('https://assiduidade.inage.gov.mz/attend/' . $hash)) }}"
                        alt="QR Code" />
                </div>
                <div class="ml-3 timeline-content" style="position:relative; margin-left: 70px">
                    <h5>Digitalize o QR Code</h5>
                    <p>Use a câmara do seu telemóvel ou app de leitura QR para escanear o código ao lado.</p>
                </div>
            </div>
        </center>

        <div class="container">
            <div class="row">
                <a href="#" class="intro-banner-vdo-play-btn pinkBg" target="_blank">
                    <span class="ripple pinkBg"></span>
                    <h4 style="color:white; font-weight:bold; position:relative; top:5px" class="mt-2">97ddb</h4>
                    <span class="ripple pinkBg"></span>
                    <span class="ripple pinkBg"></span>
                </a>
            </div>
        </div>
        <!-- Passo 3 - Esquerda -->
        <div class="timeline-step left">
            <div class="timeline-marker">3</div>
            <div class="timeline-content">
                <h5>Preencha os dados</h5>
                <p>Insira o seu email ou celular e o código apresentado à direita.</p>
            </div>
        </div>
    </div>
</div>
@endsection