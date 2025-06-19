<?php
session_start();
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // sanitizando e verificando as entradas

    $requiredKeys = ['username', 'password'];

    if (!empty(array_diff($requiredKeys, array_keys($_POST)))) {
        $errors[] = "Dados incompletos. Por favor, preencha todos os campos.";
    } else {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        if (empty($username))
            $errors[] = "Usuário é obrigatório";
        if (empty($password))
            $errors[] = "Senha é obrigatória";
    }

    if (empty($errors)) {
        try {
            // buscando usuário
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // verifica usuário e senha
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors[] = "Usuário ou senha inválidos";
            } else {

                // setando sessão
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['profile_picture'] = $user['profile_picture'];

                header("Location: home.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Erro no login: " . $e->getMessage());
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
    <title>Orkut - Login</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #D9E6F7" class="d-flex justify-content-center align-items-center vh-100 m-0">
    <div class="bg-white p-5 rounded-3 shadow-sm" style="width: 400px;">
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

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3 form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="" required>
                <label for="username" class="form-label">Usuário</label>
            </div>
            <div class="mb-3 form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="" required>
                <label for="password" class="form-label">Senha</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="text-center mt-3 text-muted">
                <a href="cadastro.php" class="text-decoration-none">Criar nova conta</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>