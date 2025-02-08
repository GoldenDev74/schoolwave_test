<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <style>
      body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
      }
      .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
      }
      .credentials {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
      }
      .login-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin: 20px 0;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Bonjour,</h2>
      <p>Voici vos informations de connexion pour accéder à l'application :</p>
      
      <div class="credentials">
        <p><strong>Email :</strong> {{ explode("\n", $contact['message'])[0] }}</p>
        <p><strong>Mot de passe :</strong> {{ explode("\n", $contact['message'])[1] }}</p>
      </div>

      <p>Pour vous connecter, cliquez sur le lien ci-dessous :</p>
      <a href="{{ explode("\n", $contact['message'])[2] }}" class="login-button">Se connecter</a>

      <p><strong>Note :</strong> Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe lors de votre première connexion.</p>
    </div>
  </body>
</html>