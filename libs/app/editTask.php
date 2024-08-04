<?php
session_start();
require '../config/functions.php';

// Si la session ne contient pas d'information sur l'user_id on intérompt tout et on affiche message d'erreur
if (!isset($_SESSION["user_id"])) {
    accessDenied();
}

require("../config/pdo.php");


if (isset($_POST["edit"]) && isset($_POST['taskEdited'])) {

    if (!empty(trim($_POST["taskEdited"], ' ')) && strlen(trim($_POST["taskEdited"], ' ')) > 2) {

        if ($_SESSION["title"] == trim($_POST['taskEdited'])) {
            header("Location: app.php");
            return;

        } else {
            $taskEdited = htmlspecialchars($_POST["taskEdited"]);

            // Si la tâche n'est pas vide et qu'elle a subit une modification on enregistre en BDD
            $sql = "UPDATE `tasks` SET `title` = :taskEdited WHERE `task_id` = :task_id";

            // Préparation de la requête ci-dessus pour l'insertion
            $stmt = $pdo->prepare($sql);

            // Injection de paramètres
            $stmt->bindValue(':taskEdited', $taskEdited, PDO::PARAM_STR);
            $stmt->bindValue(':task_id', $_SESSION["task_id"], PDO::PARAM_INT);

            // Éxécution de la modification
            $stmt->execute();


            $_SESSION["success"] = "tâche modifiée ! <span style='font-size:20px;'>&#128077;</span>";
            header("Location: app.php");
            return;
        }
    } else {
        $_SESSION["error"] = "
        Veuillez bien remplir ce champ <span style='font-size:20px;'>&#128530;</span>";
        header("Location: editTask.php");
        return;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../main.css">
    <title>Édition</title>
</head>

<body>
    <div class="container container-edit-page">
        
        <div class="containerMessages">

            <p>
                <?php if (isset($_SESSION["error"])) {
                    $message = $_SESSION["error"];
                    echo  "<div class='error-in-app' role='alert'> $message </div>";
                    unset($_SESSION["error"]);
                } ?>
            </p>
        </div>

        <form class="form-edit form-app" method="POST">
            <div>
                <h2>Éditer Une Tâche</h2>
            </div>
            <div class="form-group-edit">

                <input type="text" name="taskEdited" class="inputEditTask" value="<?= $_SESSION["title"] ?>">

                <input class="btnFirst" name="edit" type="submit" value="Éditer"></input>

            </div>
        </form>
        <div class="redirection-app">
            <a href="./app.php" class="btnSecond">Annuler</a>
        </div>
    </div>
</body>

</html>