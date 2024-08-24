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

$sql = "SELECT comentarios.id, comentarios.comentario, comentarios.data_comentario, comentarios.likes, comentarios.dislikes, usuarios.username 
        FROM comentarios 
        JOIN usuarios ON comentarios.usuario_id = usuarios.id 
        ORDER BY comentarios.likes DESC, comentarios.data_comentario DESC";


$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: #092756;
            background: -webkit-linear-gradient(135deg, #670d10, #092756);
            color: #fff;
            margin: 0;
            padding: 0;
        }

        header nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        header nav ul li {
            margin: 0 10px;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            display: block;
        }

        header nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        #commentSection {
            margin-top: 20px;
        }

        .comment-item {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .comment-item p {
            margin: 0;
        }

        .comment-item p strong {
            color: #6eb6de;
        }

        .votos {
            margin-top: 10px;
        }

        .votos button {
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .votos button:hover {
            color: #4a77d4;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="add_comment.html">Adicionar Comentário</a></li>
                <li><a href="show_comments.php">Mostrar Comentários</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Comentários</h1>
        <section id="commentSection" class="comments-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='comment-item'>";
                    echo "<p><strong>" . htmlspecialchars($row["username"]) . "</strong> (" . $row["data_comentario"] . "):</p>";
                    echo "<p>" . htmlspecialchars($row["comentario"]) . "</p>";
                    echo "<div class='votos'>";
                    echo "<form method='POST' action='vote.php' style='display:inline;'>";
                    echo "<input type='hidden' name='comentario_id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' name='vote' value='like'>👍</button> " . $row["likes"];
                    echo "<button type='submit' name='vote' value='dislike'>👎</button> " . $row["dislikes"];
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "Nenhum comentário ainda.";
            }
            $conn->close();
            ?>
        </section>
    </main>
</body>
</html>
