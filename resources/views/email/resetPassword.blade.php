<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Team List - Réinitialisation du mot de passe</title>
</head>
<body>
<h3>Bonjour {{ $mailData['name'] }}</h3>
<p>Vous avez oublié votre mot de pass <strong>Team List</strong> ?</p>
<p>Vous pouvez en créer un nouveau en suivant le lien ci-dessous :</p>
<a href="{{ $mailData['link'] }}">{{ $mailData['link'] }}</a>
<p>A bientôt</p>
<p>L'équipe Team List</p>
</body>
</html>
