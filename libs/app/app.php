<?php
session_start();
require '../config/functions.php';

// Si la session ne contient pas d'information sur l'user_id on intérompt tout et on affiche message d'erreur
if (!isset($_SESSION["user_id"])) {
   accessDenied();
}

require '../config/pdo.php';

// Ajout d'une tâche
if (isset($_POST["ajouter"])) {

   // Vérification si n'est pas vide et que c'est supérieur à 2 caractères
   if (!empty(trim($_POST["newTask"], ' ')) && strlen(trim($_POST["newTask"], ' ')) > 2) {
      // $newTask = htmlspecialchars($_POST["newTask"]);

      $sql = "INSERT INTO tasks (title, user_id) VALUES (:newTask, :user_id)";

      // Préparation de la requête ci-dessus pour l'insertion
      $stmt = $pdo->prepare($sql);

      // Injection de paramètres
      $stmt->bindValue(':newTask', $_POST["newTask"], PDO::PARAM_STR);
      $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);

      // Exécution
      $stmt->execute();

      $_SESSION["success"] = "Tâche Ajoutée <span style='font-size:20px;'>&#128077;</span>";
      header("location: app.php");
      return;
   } else {

      $_SESSION["warning"] = "Veuillez bien remplir ce champ <span style='font-size:20px;'>&#128530;</span>";
      header("location: app.php");
      return;
   }
}

// Logique pour la supprission si l'id exsiste et que le bouton action déclencher et si il match avec delete alors on supprime la tâche graçe à son id & on affiche un message
if (isset($_POST["task_id"])) {
   if (isset($_POST["delete"])) {

      $sql = "DELETE FROM tasks WHERE task_id = :task_id";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(':task_id', $_POST["task_id"], PDO::PARAM_INT);

      $delete = $stmt->execute();

      $_SESSION["warning"] = "Tâche Supprimée";
      header("location: app.php");
      return;
   }
   if (isset($_POST["edit"])) {
      $_SESSION['title'] = htmlentities($_POST['title']);

      $_SESSION['task_id'] = $_POST['task_id'];

      header("Location: editTask.php");
      return;
   }
}

// Récupération de la liste des tâches sous format tableau associatif
$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
   <script src="https://kit.fontawesome.com/daed954e81.js" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="../../main.css">
   <title>Liste de Tâches</title>
</head>

<body>
   <div class="container">
      <h1 class="">Bonjour, <br> 
      <?php 
         if (!empty($_SESSION["pseudo"])) {
            echo $_SESSION["pseudo"] ;
         }else {
            echo $_SESSION["email"] ;
         } 
      ?> </h1>
      <div class="underline"></div>

      <div class="container-formNew-flashMessage">

         <div class="containerMessages">
            <p>

               <?php if (isset($_SESSION['salutation'])) {
                  $message = $_SESSION['salutation'];
                  echo  "<div class='success' role='alert'> $message </div>";
                  unset($_SESSION['salutation']);
               } ?>
               <?php if (isset($_SESSION["success"])) {
                  $message = $_SESSION["success"];
                  echo  "<div class='success' role='alert'> $message </div>";
                  unset($_SESSION["success"]);
               } ?>
               <?php if (isset($_SESSION["warning"])) {
                  $message = $_SESSION["warning"];
                  echo  "<div class='error-in-app' role='alert'> $message </div>";
                  unset($_SESSION["warning"]);
               } ?>

            </p>
         </div>
         <form class="form-app" action="app.php" method="POST">

            <div class="form-group">
               <input type="text" class="inputData" placeholder="Ajoute une tâche" name="newTask">
               <button class="btnFirst" name="ajouter" type="submit">Ajouter</button>
            </div>
         </form>
      </div>
      <table>
         <tbody>
            <?php
            foreach ($rows as $row) {
               $tache = ' 
                        <tr>
                        
                           <div class="container-task">
                              <p>' . htmlentities($row['title']) . '</p>
                           
                              <div class="listBtn">
                                 <form action="app.php" method="POST">
                                       <input type="text" value="' . $row['task_id'] . '" name="task_id" hidden>
                                       <input type="text" value="' . htmlentities($row['title']) . '" name="title" hidden>

                                       <button class="btn-delete" name="delete" title="Supprimer">
                                          <i class="fa-solid fa-trash"></i>
                                       </button>
                                       <button name="edit" class="btn-edit" title="Editer">
                                          <i class="fa-solid fa-pen-to-square"></i>
                                       </button>
                                    </form>
                              </div>

                           </div>
                        <tr/>
               ';
               echo "<div class='separateur'></div> $tache ";
            } ?>
         </tbody>
      </table>

   </div>

   <div class="seDeconecter">
      <a type="submit" class="btnSecond" href="../security/logOut.php" name="quitter" title="Déconnexion et retour vers la page d'acceuil">Se deconnecter</a>
   </div>

</body>

</html>