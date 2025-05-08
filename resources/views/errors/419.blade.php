<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session Expirée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .error-icon {
            font-size: 80px;
            color: #6c757d;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>

<div>
    <i class="bi bi-hourglass-split error-icon"></i>
    <h1 class="mt-3">419 - Session Expirée</h1>
    <p>Votre session a expiré. Veuillez vous reconnecter.</p>
    <a href="{{ url('/') }}" class="btn btn-warning mt-3">Se reconnecter</a>
</div>

</body>
</html>
