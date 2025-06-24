<?php
require_once "db.php";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // sanitizando e verificando as entradas

    $requiredKeys = ['username', 'full_name', 'password', 'confirm_password']; // vamos verificar se todas as keys do form estão setadas no POST

    if (!empty(array_diff($requiredKeys, array_keys($_POST)))) {
        $errors[] = "Dados incompletos. Por favor, preencha todos os campos.";
    } else {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($username))
            $errors[] = "Usuário é obrigatório";
        if (empty($full_name))
            $errors[] = "Nome completo é obrigatório";
        if (empty($password))
            $errors[] = "Senha é obrigatória";
        if (empty($confirm_password))
            $errors[] = "Confirme a senha";
        if ($password !== $confirm_password)
            $errors[] = "Confirmação de senha não confere";
        if (!empty($password) && !preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
            $errors[] = "A senha deve ter pelo menos 8 caracteres, incluindo letras e números";
        }
    }

    if (empty($errors)) {
        try {
            // verificando se o username já existe 
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $errors[] = "Este nome de usuário já está em uso, ecolhia outro";
            } else {
                // criando hash da senha
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                // criando caminho + salvando arquivo com timestamp (caso exista arquivo)
                $profile_picture_path = null;
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '.' . $file_ext;
                    $target_path = $upload_dir . $file_name;

                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array(strtolower($file_ext), $allowedTypes)) {
                        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                            $profile_picture_path = $target_path;
                        }
                    }
                }

                // isert no banco com o novo usuário
                $stmt = $conn->prepare("
                    INSERT INTO users (username, full_name, password_hash, profile_picture, created_at) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$username, $full_name, $password_hash, $profile_picture_path]);

                header("Location: index.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $errors[] = "Ocorreu um erro, por favor tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Criar conta</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #D9E6F7" class="d-flex justify-content-center align-items-center vh-100 m-0">
    <div class="bg-white p-5 rounded-3 shadow-sm" style="width: 800px;">
        <div class="text-center mb-4">
            <img style="max-width: 200px" class="img-fluid" src="assets/logo.png" alt="Orkut Logo">
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

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            enctype="multipart/form-data">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="" required>
                        <label for="username" class="form-label">Usuário</label>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="" required>
                        <label for="full_name" class="form-label">Nome completo</label>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder=""
                            required>
                        <label for="password" class="form-label">Senha</label>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            placeholder="" required>
                        <label for="confirm_password" class="form-label">Confirme a senha</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Foto</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Criar nova conta</button>
                <div class="text-center mt-3  text-muted">
                    Já tem uma conta? <a href="index.php" class="text-decoration-none">Entrar</a>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>