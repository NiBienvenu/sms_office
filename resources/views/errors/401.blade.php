<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Non autorisé</title>
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
            color: #ffc107;
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
    <i class="bi bi-person-x-fill error-icon"></i>
    <h1 class="mt-3">401 - Non autorisé</h1>
    <p>Vous devez être connecté pour accéder à cette page.</p>
    <a href="{{ url('/login') }}" class="btn btn-primary mt-3">Se connecter</a>
</div>

</body>
</html>
