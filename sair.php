<?php
session_start();
unset($_SESSION['id'], $_SESSION['nome'], $_SESSION['email'], $_SESSION['permissao']);

$_SESSION['msg'] = "Você saiu do aplicativo";
header("Location: login.php");