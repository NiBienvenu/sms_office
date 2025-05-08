<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur Serveur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .error-icon {
            font-size: 80px;
            color: #721c24;
        }
        .btn-dark {
            background-color: #343a40;
            border: none;
        }
        .btn-dark:hover {
            background-color: #23272b;
        }
    </style>
</head>
<body>

<div>
    <i class="bi bi-bug-fill error-icon"></i>
    <h1 class="mt-3">500 - Erreur Serveur</h1>
    <p>Une erreur interne s'est produite. Nos équipes travaillent dessus !</p>
    <a href="{{ url('/') }}" class="btn btn-dark mt-3">Retour à l'accueil</a>
</div>

</body>
</html>
