<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aplicacao_web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Previne SQL Injection
        $email = $conn->real_escape_string($email);

        $sql = "SELECT id, username, password FROM usuarios WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION['usuario_id'] = $row["id"];
                $_SESSION['username'] = $row["username"];
                header("Location: index.html"); // Redireciona para a página inicial ou para a página desejada
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "Nenhum usuário encontrado com este e-mail.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

$conn->close();
?>
