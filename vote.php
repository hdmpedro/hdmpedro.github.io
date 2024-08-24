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
    if (isset($_SESSION['usuario_id']) && isset($_POST['comentario_id']) && isset($_POST['vote'])) {
        $usuario_id = $_SESSION['usuario_id'];
        $comentario_id = $_POST['comentario_id'];
        $tipo_voto = $_POST['vote']; // 'like' ou 'dislike'

        // Previne SQL Injection
        $comentario_id = $conn->real_escape_string($comentario_id);
        $tipo_voto = $conn->real_escape_string($tipo_voto);

        // Verifica se o usuário já votou neste comentário
        $sql_check = "SELECT * FROM votos WHERE comentario_id = '$comentario_id' AND usuario_id = '$usuario_id'";
        $result_check = $conn->query($sql_check);

        if ($result_check === FALSE) {
            die("Erro na consulta: " . $conn->error);
        }

        if ($result_check->num_rows > 0) {
            echo "Você já votou neste comentário.";
        } else {
            // Adiciona o voto
            $sql = "INSERT INTO votos (comentario_id, usuario_id, tipo_voto) VALUES ('$comentario_id', '$usuario_id', '$tipo_voto')";
            if ($conn->query($sql) === TRUE) {
                // Atualiza contagem de votos
                $sql_update = "UPDATE comentarios 
                               SET likes = (SELECT COUNT(*) FROM votos WHERE comentario_id = '$comentario_id' AND tipo_voto = 'like'),
                                   dislikes = (SELECT COUNT(*) FROM votos WHERE comentario_id = '$comentario_id' AND tipo_voto = 'dislike')
                               WHERE id = '$comentario_id'";
                if ($conn->query($sql_update) === FALSE) {
                    die("Erro ao atualizar contagem de votos: " . $conn->error);
                }

                header("Location: show_comments.php"); // Redireciona para a página de comentários
                exit();
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Dados do voto incompletos ou usuário não autenticado.";
    }
}

$conn->close();
?>
