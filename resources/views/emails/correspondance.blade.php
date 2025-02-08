<!DOCTYPE html>
<html>
<head>
    <title>{{ $correspondance->objet }}</title>
</head>
<body>
    <h2>{{ $correspondance->objet }}</h2>
    <div>{!! $correspondance->message !!}</div>
    <hr>
    <p>Envoyé par : {{ $correspondance->sender->name }} ({{ $correspondance->sender->email }})</p>
    <p>Ne pas répondre à cet email</p>
</body>
</html>