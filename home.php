<?php
require_once 'auth.php';
require_once 'db.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit;
}

$errors = [];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accept_request']) and isset($_POST['requester_id'])) {
        $accept_request = ($_POST['accept_request'] === 'true');
        $requester_id = htmlspecialchars($_POST['requester_id']);

        if (empty($requester_id)) {
            $errors[] = 'Dados de formulário incompletos';
        }

    } else {
        $errors[] = 'Dados de formulário incompletos';
    }

    if (empty($errors)) {

        $friendship_status = $accept_request == true ? 'accepted' : 'rejected';

        $stmt = $conn->prepare(
            "UPDATE
                        friends f
                    SET
                        f.status = ?
                    WHERE
                        f.user_id = ?
                        AND f.friend_id = ?;"
        );

        $stmt->execute([$friendship_status, $requester_id, $user_id]);
    }
}

// buscando amigos
$stmt = $conn->prepare(
    "SELECT DISTINCT u.full_name, u.profile_picture, (SELECT COUNT(*) FROM friends f2 WHERE (
                            (f2.user_id = u.id AND f2.status = 'accepted') OR (f2.friend_id = u.id AND f2.status = 'accepted'))) 
                            AS total_friends
                FROM users u
                JOIN friends f ON (
                    (f.user_id = ? AND f.friend_id = u.id AND f.status = 'accepted') OR
                    (f.friend_id = ? AND f.user_id = u.id AND f.status = 'accepted')
                ) ORDER BY u.full_name LIMIT 6;"
);

$stmt->execute([$user_id, $user_id]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

// buscando requests de amizade (status = pending)
$stmt = $conn->prepare(
    "SELECT DISTINCT
                    u.id,
                    u.full_name,
                    u.profile_picture
                FROM
                    users u
                JOIN friends f ON f.user_id = u.id
                WHERE f.friend_id = ? AND f.status = 'pending';"
);

$stmt->execute([$user_id]);
$friendship_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// buscando mensagens não lidas (is_read = false)
$stmt = $conn->prepare(
    "SELECT
                    u.id,
                    u.full_name,
                    u.profile_picture,
                    m.content
                FROM
                    users u
                INNER JOIN messages m ON m.sender_id = u.id
                WHERE m.receiver_id = ? AND m.is_read = 0;
                "
);

$stmt->execute([$user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Principal</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="global.css">
</head>

<body>
    <div class="container-fluid min-vh-100">
        <div class="row d-flex justify-content-center vh-100">
            <?php include "sidebar.php" ?>

            <div class="col-md-9 p-4">
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
                <div class="row">
                    <!-- Principal - Mensagens e solicitações de amizade -->
                    <div class="col-md-8">
                        <h4 class="mb-3">Principal</h4>

                        <?php if (empty($friendship_requests) && empty($messages)): ?>
                            <div class="card message-card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">Nenhuma atividade encontrada.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($friendship_requests as $request): ?>
                                <div class="card message-card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($request['profile_picture']) ?>"
                                                class="friend-img me-3">
                                            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold"><?= htmlspecialchars($request['full_name']) ?> pediu sua
                                                    amizade.</div>
                                                <div class="d-flex">
                                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                                                        method="post">
                                                        <input type="hidden" name="accept_request" value="true">
                                                        <input type="hidden" name="requester_id" value="<?= $request['id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm me-1"><i
                                                                class="bi bi-check"></i>Aceitar</button>
                                                    </form>
                                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                                                        method="post">
                                                        <input type="hidden" name="accept_request" value="false">
                                                        <input type="hidden" name="requester_id" value="<?= $request['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="bi bi-x"></i>Recusar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php foreach ($messages as $message): ?>
                                <div class="card message-card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <img src="<?= htmlspecialchars($message['profile_picture']) ?>"
                                                class="friend-img me-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fw-bold"><?= htmlspecialchars($message['full_name']) ?> enviou
                                                        uma mensagem.</div>
                                                    <div>
                                                        <a href="mensagem.php?friend=<?= $message['id'] ?>">
                                                            <button class="ms-3 btn btn-outline-secondary btn-sm">Enviar
                                                                mensagem</button>
                                                        </a>
                                                    </div>
                                                </div>
                                                <p class="card-text mt-1 mb-0"><?= htmlspecialchars($message['content']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Amigos -->
                    <div class="col-md-4">
                        <h4 class="mb-3">Amigos</h4>
                        <div class="row row-cols-1 g-3">
                            <?php if (empty($friends)): ?>
                                <div class="col">
                                    <div class="card friend-card p-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="friend-name mb-0">Nenhum amigo encontrado.</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($friends as $friend): ?>
                                    <div class="col">
                                        <div class="card friend-card p-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($friend['profile_picture']) ?>"
                                                    class="friend-img me-3">
                                                <div>
                                                    <h6 class="friend-name mb-0">
                                                        <?= htmlspecialchars($friend['full_name']) ?>
                                                        (<?= $friend['total_friends'] ?>)
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <button class="btn btn-link w-100 mt-3 <?= count($data['friends']) < 6 ? 'd-none' : '' ?>">
                                Ver todos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>