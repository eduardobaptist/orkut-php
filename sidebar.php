<?php
require_once "auth.php";

if (!is_logged_in()) {
    header("Location: index.php");
    exit;
}

$profile_picture = $_SESSION['profile_picture'];
$full_name = $_SESSION['full_name'];
$username = $_SESSION['username'];

echo <<<HTML
    <div class='col-md-3 col-lg-2 d-none d-md-block p-0 my-4'>
        <div class='bg-light h-100 d-flex flex-column p-4 rounded shadow-sm'>

            <div class='text-center mb-4'>
                <div class='mb-4'>
                    <img src='assets/logo.png' alt='Orkut' class='img-fluid' style='max-width: 200px;'>
                </div>
                <img src='$profile_picture' alt='Profile' class='profile-img mb-2'>
                <h5 class='user-name'>$full_name</h5>
                <p class='text-secondary'>@$username</p>
            </div>

            <div class='d-flex flex-column gap-1 mb-4'>
                <a href="home.php">
                    <button class='btn btn-outline-secondary w-100 mb-2 text-start'>
                        <i class="bi bi-house-door me-2"></i> Principal
                    </button>
                </a>

                <button class='btn btn-outline-secondary w-100 mb-2 text-start'>
                    <i class='bi bi-pencil-square me-2'></i> Editar perfil
                </button>
                
                <a href="addamigo.php">
                    <button class='btn btn-outline-secondary w-100 mb-2 text-start'>
                        <i class='bi bi-person-plus me-2'></i> Adicionar amigo
                    </button>
                </a>
                
                <a href="mensagem.php"> 
                    <button class='btn btn-outline-secondary w-100 mb-2 text-start'>
                        <i class='bi bi-envelope me-2'></i> Enviar mensagem
                    </button>
                </a>
            </div>

            <div class='mt-auto'>
                <a href='logout.php' class='btn btn-outline-danger w-100'>
                    <i class='bi bi-box-arrow-right me-2'></i> Sair
                </a>
            </div>

        </div>
    </div>
HTML;
?>