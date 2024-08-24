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
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografa a senha

        // Previne SQL Injection
        $username = $conn->real_escape_string($username);
        $email = $conn->real_escape_string($email);

        // Verifica se o nome de usuário ou o e-mail já existem
        $sql_check = "SELECT * FROM usuarios WHERE username = '$username' OR email = '$email'";
        $result_check = $conn->query($sql_check);

        if ($result_check === FALSE) {
            die("Erro na consulta: " . $conn->error);
        }

        if ($result_check->num_rows > 0) {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="refresh" content="3;url=register.html">
                <link rel="stylesheet" href="styles1.css">
                <title>Erro no Cadastro</title>
                <style>
                    body {  background: #092756; font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                    .message { padding: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; display: inline-block; }
                </style>
            </head>
            <body>
                <div class="message">
                    <h1>Erro no Cadastro</h1>
                    <p>Nome de usuário ou e-mail já estão em uso. Você será redirecionado de volta para a página de registro.</p>
                </div>
            </body>
            </html>';
        } else {
            // Adiciona o novo usuário
            $sql = "INSERT INTO usuarios (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                
                $_SESSION['usuario_id'] = $conn->insert_id;  // ID do usuário recém-criado
                $_SESSION['username'] = $username;


                // Redireciona após a confirmação
                echo '<!DOCTYPE html>
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="styles1.css">
                    <meta http-equiv="refresh" content="1.5;url=index.html">
                    <title>Cadastro Concluído</title>
                    <style>
                        .body { display: flex; justify-content: center; font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                        .message { display: flex; justify-content: center; text-align: center; padding: 40px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; display: inline-block; }
                    </style>
                </head>
                <body>
                    <div class="message">
                        <h1>Cadastro Concluído!</h1>
                        <p>Você será redirecionado para a página inicial!</p>
                    </div>
                </body>
                </html>';
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Dados incompletos.";
    }
}

$conn->close();
?>
