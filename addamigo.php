<?php
require_once 'auth.php';
require_once 'db.php';

$errors = [];

if (!is_logged_in()) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$users = fetch_users($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['friend_id'])) {
        $errors[] = "Selecione o usuário";
    } else {
        $friend_id = htmlspecialchars($_POST['friend_id']);

        $valid_ids = array_column($users, 'id');
        if (!in_array($friend_id, $valid_ids)) {
            $errors[] = "Usuário inválido";
        }

    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)"
        );

        $stmt->execute([$user_id, $friend_id]);

        $users = fetch_users($conn, $user_id);
    }


}

if (empty($users)) {
    $errors[] = "Nenhum usuário disponível para solicitar amizade";
}

function fetch_users($conn, $user_id)
{
    $stmt = $conn->prepare(
        "WITH cte_user_friends AS (
                SELECT friend_id AS user_id FROM friends WHERE user_id = ? AND status IN ('pending', 'accepted')
                UNION
                SELECT user_id AS user_id FROM friends WHERE friend_id = ? AND status IN ('pending', 'accepted')
            )

            SELECT 
                u.id,
                u.full_name,
                u.profile_picture,
                (
                    SELECT COUNT(*)
                    FROM friends f
                    WHERE (f.user_id = u.id OR f.friend_id = u.id)
                    AND f.status = 'accepted'
                ) AS total_friends
            FROM users u
            WHERE u.id != ?
            AND u.id NOT IN (SELECT user_id FROM cte_user_friends)
            ORDER BY u.full_name;"
    );

    $stmt->execute([$user_id, $user_id, $user_id]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Adicionar amigo</title>
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

            <!-- Principal - Usuários -->
            <div class="col-md-9 p-4">
                <h4 class="mb-3">Adicionar amigo</h4>
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

                <?php if (!empty($users)): ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                        class="row g-2 d-flex align-items-center">
                        <div class="col-6">
                            <input class="form-control d-flex" placeholder="Buscar usuário..." id="search" name="search"
                                required>
                        </div>
                        <div class="col-1"><button class="btn btn-primary w-75" type="submit">Ok</button></div>
                    </form>
                    <hr>
                    <div class="row row-cols-1 g-3">

                        <?php foreach ($users as $user): ?>
                            <div class='col'>
                                <div class='card friend-card p-3'>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                                        class="d-flex align-items-center justify-content-between">
                                        <div class='d-flex align-items-center'>
                                            <img src="<?= $user['profile_picture'] ?>" class='friend-img me-3'>
                                            <div>
                                                <h6 class='friend-name mb-0'><?= $user['full_name'] ?>
                                                    (<?= $user['total_friends'] ?>)</h6>
                                            </div>
                                            <input type="hidden" name="friend_id" value="<?= $user['id'] ?>">
                                        </div>
                                        <button type="submit" class="btn btn-success ms-3">
                                            <i class="bi bi-person-plus"></i> Adicionar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>