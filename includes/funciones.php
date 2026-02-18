<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($ruta) {
    $url_actual = $_SERVER['REQUEST_URI'];

    // quitar parámetros GET
    $url_actual = strtok($url_actual, '?');

    return $url_actual === "/admin{$ruta}";
}

function is_auth(): bool{
    session_start();
    return isset($_SESSION['nombre']) && !empty($_SESSION) ;

}

function is_admin():bool{
    session_start();
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}
