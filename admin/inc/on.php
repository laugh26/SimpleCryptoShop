<?php

    session_start();

    include '../include/db.php';

    if (isset($_SESSION['plainuser']) && isset($_SESSION['password'])) {
        $rez = DataBase("SELECT `username`, `password` FROM `admins`");

        if (!(password_verify($_SESSION['plainuser'], $rez['username'])) || !(password_verify($_SESSION['password'], $rez['password']))) {
            header('Location: login/');
        }
    } else {
        header('Location: login/');
    }
?>