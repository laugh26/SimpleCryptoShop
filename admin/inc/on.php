<?php

    session_start();

    include '../include/db.php';

    if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
        $user = preg_replace("[^\w\d\s]", "", $_SESSION['user']);
        $passw = preg_replace("[^\w\d\s]", "", $_SESSION['password']);
        $rez = DataBase("SELECT COUNT(*) FROM `admins` WHERE `username` = '$user' AND `password` = '$passw'")[0];

        if ($rez != 1) {
            header('Location: login/');
        }
    } else {
        header('Location: login/');
    }
?>