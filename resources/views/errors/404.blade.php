<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Non Trouvée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .error-icon {
            font-size: 80px;
            color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div>
    <i class="bi bi-exclamation-triangle-fill error-icon"></i>
    <h1 class="mt-3">404 - Page Non Trouvée</h1>
    <p>Oups ! La page que vous cherchez n'existe pas.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Retour à l'accueil</a>
</div>

</body>
</html>
