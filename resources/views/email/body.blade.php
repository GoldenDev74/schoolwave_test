<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<<<<<<< HEAD
    <title>{{ $contact['subject'] }}</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2 style="color: #1a73e8;">Message de {{ $contact['nom'] }}</h2>
=======
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2 style="color: #1a73e8;">Message de M/Mme {{ $contact['nom'] }}</h2>
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
    
    <p><strong>Expéditeur :</strong> {{ $contact['email'] }}</p>
    <p><strong>Sujet :</strong> {{ $contact['subject'] }}</p>
    
<<<<<<< HEAD
    <div style="background: #f8f9fa; padding: 15px; margin: 15px 0; font-size: 16px;">
        {!! $contact['message'] !!}
=======
    <div style="background: #f8f9fa; padding: 15px; margin: 15px 0;">
        {{ $contact['message'] }}
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
    </div>

    <hr style="border: 1px solid #eee; margin: 20px 0;">
    
    <small style="color: #666;">
        Envoyé via WEBEES<br>
        © {{ date('Y') }} Tous droits réservés
    </small>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
