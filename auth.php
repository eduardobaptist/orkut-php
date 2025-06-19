<?php
session_start();

// função pra ser chamada nas telas privadas; verifica se todas as variáveis de sessão estão setadas

function is_logged_in()
{
    return isset($_SESSION['logged_in'])
        && $_SESSION['logged_in'] === true
        && isset($_SESSION['user_id'])
        && isset($_SESSION['username']);
}