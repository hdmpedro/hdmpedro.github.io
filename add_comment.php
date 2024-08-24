<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aplicacao_web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['usuario_id'])) {
        $usuario_id = $_SESSION['usuario_id'];
        $comentario = $_POST["comentario"];

        $sql = "INSERT INTO comentarios (usuario_id, comentario) VALUES ('$usuario_id', '$comentario')";

        if ($conn->query($sql) === TRUE) {
            header("Location: show_comments.php");
            exit();
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Se o usuário não estiver logado, exibe uma mensagem e redireciona para a página de login após 2 segundos
        echo "<script>
            alert('Você precisa estar logado para comentar.');
            setTimeout(function() {
                window.location.href = 'login.html';
            }, 2000);
        </script>";
        exit();
    }
}

$conn->close();
?>
