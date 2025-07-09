<!DOCTYPE html>
<html>
<body>
    <p>Olá {{ $participant->name }},</p>

    <p>Segue o código QR que dá acesso ao seu cartão de visita digital:</p>

    <ul>
        <li><strong>Nome:</strong> {{ $participant->name }} {{ $participant->last_name }}</li>
        <li><strong>Contacto:</strong> {{ $participant->phone_number }}</li>
        <li><strong>Email:</strong> {{ $participant->email }}</li>
    </ul>

    <div style="text-align: center; margin-top: 20px;">
        <p><strong>Guarde o código abaixo para garantir sua entrada no evento:</strong></p>
        <img src="cid:qrcodeimg" alt="QR Code" style="max-width: 200px; height: auto;" />
        <p style="font-size: 12px; color: #888;">Clique com o botão direito para salvar</p>
    </div>
</body>
</html>
