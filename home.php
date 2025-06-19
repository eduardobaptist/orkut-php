<?php
require_once 'auth.php';
if (!is_logged_in()) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orkut - Principal</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #D9E6F7;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: white;
            height: 100vh;
            position: fixed;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgb(224, 221, 221);
        }

        .user-name {
            font-weight: bold;
            color: #4B6C9D;
            font-size: 1.2rem;
        }

        .user-handle {
            color: #666;
        }

        .message-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .friend-card {
            border: none;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .friend-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgb(224, 221, 221);
        }

        .friend-name {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .friend-count {
            font-size: 0.8rem;
            color: #E94100;
        }

        .btn-orkut {
            background-color: #E94100;
            color: white;
            border: none;
        }

        .btn-orkut:hover {
            background-color: #d1401f;
            color: white;
        }

        .sidebar-btn {
            width: 100%;
            margin-bottom: 10px;
            text-align: left;
            padding: 10px 15px;
        }

        .see-all-btn {
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar p-0">
                <div class="col-md-3 col-lg-2 d-none d-md-block sidebar p-0">
                    <div class="d-flex flex-column p-4" style="height: 100vh;"> 
                        
                        <div class="text-center mb-4">
                            <div class="text-center mb-4">
                                <img style="max-width: 200px" class="img-fluid" src="assets/logo.png" alt="Orkut Logo">
                            </div>
                            <img src="<?= $_SESSION['profile_picture'] ?>" alt="Profile" class="profile-img mb-2">
                            <h5 class="user-name"><?= $_SESSION['full_name'] ?></h5>
                            <p class="user-handle">@<?= $_SESSION['username'] ?></p>
                        </div>

                        
                        <div class="d-flex flex-column mb-4">
                            <button class="btn btn-outline-primary sidebar-btn">
                                <i class="bi bi-pencil-square me-2"></i> Editar perfil
                            </button>
                            <button class="btn btn-outline-primary sidebar-btn">
                                <i class="bi bi-person-plus me-2"></i> Adicionar amigo
                            </button>
                            <button class="btn btn-outline-primary sidebar-btn">
                                <i class="bi bi-envelope me-2"></i> Enviar mensagem
                            </button>
                        </div>

                        
                        <div class="mt-auto">
                            <a href="logout.php" class="btn btn-outline-danger w-100">
                                <i class="bi bi-box-arrow-right me-2"></i> Sair
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-auto p-4">
                <div class="row">
                    <!-- Left Column - Message -->
                    <div class="col-md-8">
                        <!-- Message Card -->
                        <div class="card message-card mb-4">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Mark_Zuckerberg_F8_2019_Keynote_%2832830578717%29_%28cropped%29.jpg"
                                        alt="Mark Zuckerberg" class="friend-img me-3">
                                    <div>
                                        <h5 class="card-title">Merk Zuckerberg enviou uma mensagem para você</h5>
                                        <p class="card-text">
                                            <i class="bi bi-quote me-1"></i>
                                            Obrigado por aquecer o terreno.
                                            Refinei o que você começou... e adicionei uma timeline.
                                            <br>Abraços!
                                        </p>
                                        <button class="btn btn-orkut">
                                            <i class="bi bi-reply me-1"></i> Responder
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Friends -->
                    <div class="col-md-4">
                        <h4 class="mb-3">Amigos</h4>

                        <div class="row row-cols-1 g-3">
                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/34/Elon_Musk_Royal_Society_%28crop2%29.jpg"
                                            alt="Elon Musk" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Elon Musk</h6>
                                            <p class="friend-count mb-0">501 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Tim_Cook_2009_cropped.jpg/800px-Tim_Cook_2009_cropped.jpg"
                                            alt="Tim Cook" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Tim Cook</h6>
                                            <p class="friend-count mb-0">420 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jensen_Huang_2019.jpg/800px-Jensen_Huang_2019.jpg"
                                            alt="Jensen Huang" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Jensen Huang</h6>
                                            <p class="friend-count mb-0">427 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d6/Sundar_pichai.png/800px-Sundar_pichai.png"
                                            alt="Sunder Pichai" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Sunder Pichai</h6>
                                            <p class="friend-count mb-0">420 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/Jeff_Bezos_at_Amazon_Spheres_Grand_Opening_in_Seattle_-_2018_%2839074799225%29_%28cropped%29.jpg/800px-Jeff_Bezos_at_Amazon_Spheres_Grand_Opening_in_Seattle_-_2018_%2839074799225%29_%28cropped%29.jpg"
                                            alt="Jeff Bezos" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Jeff Bezos</h6>
                                            <p class="friend-count mb-0">321 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card friend-card p-3">
                                    <div class="d-flex align-items-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/18/Mark_Zuckerberg_F8_2019_Keynote_%2832830578717%29_%28cropped%29.jpg"
                                            alt="Mark Zuckerberg" class="friend-img me-3">
                                        <div>
                                            <h6 class="friend-name mb-0">Mark Zuckerberg</h6>
                                            <p class="friend-count mb-0">321 mensagens</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-outline-primary see-all-btn">
                            Ver todos <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>