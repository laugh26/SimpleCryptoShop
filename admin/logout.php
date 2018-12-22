<?php

    session_start();

    $_SESSION['user'] = '';
    $_SESSION['password'] = '';

    header('Location: /admin');

?>