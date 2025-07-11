<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cartão de Contacto</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 420px; margin: auto; background: #ffffff; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <!-- Introdução -->
        <p style="font-size: 15px; color: #333; margin-bottom: 20px;">
            As informações abaixo correspondem ao cartão digital de contacto profissional, registados na plataforma de eventos desenvolvida pelo INAGE. Guarde o código QR em anexo para referência ou eventos futuros.
        </p>

        <!-- Avatar com iniciais -->
        <div style="width: 80px; height: 80px; margin: auto; background-color: #6b7280; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; font-weight: bold;">
            {{ strtoupper(substr($participant->name, 0, 1)) }}{{ strtoupper(substr($participant->last_name, 0, 1)) }}
        </div>

        <!-- Nome e detalhes profissionais -->
        <h2 style="margin: 15px 0 5px 0; color: #1f2937;">{{ $participant->name }} {{ $participant->last_name }}</h2>
        <p style="margin: 0; font-size: 16px; color: #6b7280;">{{ $participant->degree }}</p>
        <p style="margin: 5px 0 20px 0; font-size: 15px; color: #374151;">
           {{ $participant->nome_org }}
        </p>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">

        <!-- Contactos -->
        <p style="margin: 10px 0; font-size: 14px; color: #111;">
            📞 <strong>{{ $participant->phone_number ?? 'Não disponível' }}</strong>
        </p>
        <p style="margin: 10px 0; font-size: 14px; color: #111;">
            ✉️ <strong>{{ $participant->email ?? 'Não disponível' }}</strong>
        </p>
        <p style="margin: 10px 0; font-size: 14px; color: #111;">
            🏠 <strong>{{ $participant->location ?? 'Endereço não informado' }}</strong>
        </p>
        <p style="margin: 10px 0; font-size: 14px; color: #111;">
            🌐 <strong><a href="{{ $participant->website }}" style="color: #2563eb; text-decoration: none;">
                {{ $participant->website ?? 'Website não disponível' }}</a></strong>
        </p>


        <!-- Rodapé -->
        <hr style="margin: 30px 0 15px 0; border: none; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 12px; color: #6b7280; line-height: 1.5;">
            Em caso de dúvidas sobre as informações contidas neste cartão digital,<br>
            entre em contacto com o INAGE,IP.
        </p>
    </div>
</body>
</html>
