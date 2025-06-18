<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #D9E6F7" class="d-flex justify-content-center align-items-center vh-100 m-0">
    <div class="bg-white p-5 rounded-3 shadow-sm" style="width: 400px;">
        <div class="text-center mb-4">
            <img style="max-width: 200px" class="img-fluid" src="assets/logo.png" alt="Orkut Logo">
        </div>
        <form>
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuário:</label>
                <input type="text" class="form-control" id="usuario" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="text-center mt-3 text-muted">
                <a href="pages/cadastro.php" class="text-decoration-none">Criar nova conta</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>