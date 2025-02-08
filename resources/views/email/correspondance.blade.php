<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $correspondance['subject'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2 style="color: #1a73e8;">Message de M/Mme {{ $correspondance['nom'] }}</h2>
    
    <p><strong>Expéditeur :</strong> {{ $correspondance['email'] }}</p>
    <p><strong>Sujet :</strong> {{ $correspondance['subject'] }}</p>
    
    <div style="background: #f8f9fa; padding: 15px; margin: 15px 0;">
        {!! $correspondance['message'] !!}
    </div>

    <hr style="border: 1px solid #eee; margin: 20px 0;">
    
    <small style="color: #666;">
        Envoyé via WEBEES<br>
        © {{ date('Y') }} Tous droits réservés
    </small>
</body>
</html>