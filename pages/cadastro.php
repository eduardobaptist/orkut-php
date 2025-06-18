<?php
$errors = [];
// TODO: sanitizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (empty($_POST['username']))
        $errors[] = "Usuário é obrigatório";

    if (empty($_POST['full_name']))
        $errors[] = "Nome completo é obrigatório";

    if (empty($_POST['password']))
        $errors[] = "Senha é obrigatória";

    if (empty($_POST['confirm_password']))
        $errors[] = "Confirme a senha";

    if ($_POST['password'] !== $_POST['confirm_password'])
        $errors[] = "Confirmação de senha não confere";


    if (empty($errors)) {

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Criar conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #D9E6F7" class="d-flex justify-content-center align-items-center vh-100 m-0">
    <div class="bg-white p-5 rounded-3 shadow-sm" style="width: 800px;">
        <div class="text-center mb-4">
            <img style="max-width: 200px" class="img-fluid" src="../assets/logo.png" alt="Orkut Logo">
        </div>

        <div class="w-100 my-2">
            <?php
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        $error
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
                     </div>";
            }
            ?>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="username" class="form-label">Usuário:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="full_name" class="form-label">Nome completo:</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="password" name="password" required required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="confirm_password" class="form-label">Confirme a senha:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                        required>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Foto:</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Criar nova conta</button>
                <div class="text-center mt-3  text-muted">
                    Jã tem uma conta? <a href="../" class="text-decoration-none">Entrar</a>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>