<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ./login/");
        exit;
    }

    session_destroy();
    header("Location: ../login/");
    exit;
}
