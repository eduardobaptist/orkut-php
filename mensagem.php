<?php
require_once 'auth.php';
require_once 'db.php';

$errors = [];

if (!is_logged_in()) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selected_friend_id = '';

$stmt = $conn->prepare(
    "SELECT DISTINCT u.id, u.full_name, u.profile_picture
                FROM users u
                JOIN friends f ON (
                    (f.user_id = ? AND f.friend_id = u.id AND f.status = 'accepted') OR
                    (f.friend_id = ? AND f.user_id = u.id AND f.status = 'accepted')
                ) ORDER BY u.full_name;"
);

$stmt->execute([$user_id, $user_id]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($friends)) {
    $errors[] = "Nenhum amigo foi encontrado para enviar a mensagem";
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['friend'])) {
        $selected_friend_id = $_GET['friend'];
    }

    if (!empty($selected_friend_id) and !in_array($selected_friend_id, array_column($friends, 'id'))) {
        $errors[] = 'O usuário selecionado não é um de seus amigos';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredKeys = ['friend_id', 'message']; // vamos verificar se todas as keys do form estão setadas no POST

    if (!empty(array_diff($requiredKeys, array_keys($_POST)))) {
        $errors[] = "Dados incompletos. Por favor, preencha todos os campos.";
    } else {
        $friend_id = intval($_POST["friend_id"]);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        if (empty($friend_id))
            $errors[] = "Destinatário é obrigatório";
        if (empty($message))
            $errors[] = "Mensagem é obrigatória";
        if (empty($friends))
            $errors[] = "Amigo não encontrado";
        if (!in_array($friend_id, array_column($friends, 'id')))
            $errors[] = "Este destinatário não é seu amigo";

    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO messages (sender_id, receiver_id, content) 
                        VALUES (?, ?, ?);"
            );

            $stmt->execute([$user_id, $friend_id, $message]);
        } catch (PDOException $e) {
            error_log("Erro no envio de mensagem: " . $e->getMessage());
            $errors[] = "Ocorreu um erro, por favor tente novamente.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Enviar mensagem</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="global.css">
</head>

<body>
    <div class="container-fluid min-vh-100">
        <div class="row d-flex justify-content-center vh-100">
            <!-- Sidebar -->
            <?php include "sidebar.php" ?>

            <!-- Principal - Mensagem -->
            <div class="col-md-9 p-4">
                <h4 class="mb-3">Enviar mensagem</h4>
                <div class="w-100 my-2">
                    <?php
                    foreach ($errors as $error) {
                        echo <<<HTML
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            $error
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
                        </div>
                        HTML;
                    }
                    ?>
                </div>

                <?php if (!empty($friends)): ?>
                    <div class='card message-card mb-3'>
                        <div class='card-body'>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="mb-3">
                                    <label for="friend_id" class="form-label">Enviar mensagem para:</label>
                                    <select class="form-select" id="friend_id" name="friend_id">
                                        <?php foreach ($friends as $friend): ?>
                                            <option <?php echo $selected_friend_id == $friend['id'] ? 'selected' : '' ?>
                                                value='<?= $friend['id'] ?>'><?= $friend['full_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="" id="message" style="height: 200px"
                                        name="message" required></textarea>
                                    <label for="message">Mensagem</label>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-outline-success">Enviar mensagem</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>