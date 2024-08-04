<?php
session_start();
require "./../config/pdo.php";

if (isset($_POST['quitter']) || isset($_SESSION["user_id"])) {
    header('location: ../../index.php');
    session_destroy();
    return;
}

// à partir du moment où l'utilisateur valide le formulaire
if (isset($_POST["register"])) {

    // Vérification si les données éxsistes et sont pas vide 
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirmPassword"])) {
        if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirmPassword"])) {

            // vérification de la validité de l'email
            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                // Préparation de la requête
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

                // Injection de paramètres
                $stmt->bindValue(":email", $_POST['email'], PDO::PARAM_STR);

                // Exécution
                $stmt->execute();
                
                // Récupération de l'user 
                $user = $stmt->fetch();

                // Vérification si l'utilisateur est déja enregistré
                if ($user) {

                    $_SESSION["error"] = "Email déja utilisé, pour se connecter veuillez suivre ce lien <a class='link-redirection' href='./login.php'>Login</a>";
                    header("location: register.php");
                    return;

                    // Vérification que les mots de pass sont identiques
                } elseif ($_POST["password"] !== $_POST["confirmPassword"]) {

                    // sauvegarde le pseudo pour une auto complétion si erreur 
                    $_SESSION['pseudo'] = $_POST["pseudo"];

                    // sauvegarde de l'émail pour une auto complétion si erreur 
                    $_SESSION['email'] = $_POST["email"];

                    $_SESSION["error"] = "Veuillez renseigner le même mot de passe dans les champs correspondants";
                    header("location: register.php");
                    return;

                } else {
                    $codeVerify = $_POST["password"];
                    $emailVerify = $_POST["email"];
                    $pseudo = $_POST["pseudo"] ?? "";

                    // HASHAGE DE MOT DE PASS 
                    $codeHash = password_hash($codeVerify, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (pseudo, email, password) VALUE (:pseudo, :email, :password)";

                    // Préparation à l'insertion en BD
                    $stmt = $pdo->prepare($sql);

                    // Injection des paramètres de la requête passé à $sql
                    $stmt->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
                    $stmt->bindValue(":email", $emailVerify, PDO::PARAM_STR);
                    $stmt->bindValue(":password", $codeHash, PDO::PARAM_STR);

                    $stmt->execute();
                    
                    $_SESSION['email'] = $_POST["email"];
                    header("Location: ./login.php");
                    return;
                }

            } else {
                $_SESSION["error"] =  "L'adresse mail est invalide";
                header("location: register.php");
                return;
            }

        } else {
            $_SESSION["error"] = "l'email et le mot de passe sont requis, Merci de remplir tout les champs";
            header("location: register.php");
            return;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="description" content="Inscription gratuite à notre Application de Gestion de tâches">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../main.css">
    <title>S'enregistrer</title>
</head>

<body>
    <div class="container">

        <h1>Enregistrez Vous</h1>
        <div class="underline"></div>

        <div class="containerMessages">
                    <?php if (isset($_SESSION['error'])) {
                        echo '<div class="error-in-app" role="alert">'. $_SESSION['error'] .'</div>';
                        unset($_SESSION['error']);
                    } ?>
        </div>

        <form class="form" action="./register.php" method="POST" novalidate>
            <div class="form-group">

                <label for="focusInputPseudo"> Pseudo</label>
                <input type="text" name="pseudo" class="inputData" id="focusInputPseudo" placeholder=" Entez votre pseudo" aria-label=" pseudo" 
                value="<?php if(isset($_SESSION['pseudo']))
                            { echo htmlentities($_SESSION['pseudo']);}?>">
            </div>
            <div class="form-group">

                <label for="focusInputEmail"> Adresse Electronique *</label>
                <input type="email" name="email" class="inputData" id="focusInputEmail" placeholder=" Entez votre email" aria-label=" adress email" 
                value="<?php if(isset($_SESSION['email']))
                            { echo htmlentities($_SESSION['email']);}?>">
            </div>
            <div class="form-group">

                <label for="focusInputPassW"> Mot de Passe *</label>
                <input type="password" name="password" class="inputData" id="focusInputPassW" placeholder=" Mot de passe" aria-describedby="passwordHelpBlock">
                <small id="passwordHelpBlock">pour votre sécurité veuillez utiliser un mot de passe fort</small>
            </div>
            <div class="form-group">

                <label for="focusInputPassW2"> Confirmer le mot de passe *</label>
                <input type="password" name="confirmPassword" class="inputData" placeholder=" Confirmez mot de passe" id="focusInputPassW2" aria-label="confirmation du mot de passe">
            </div>
            <div class="container-btns">

                <button type="submit" class="btnFirst" name="register" title="soumettre le formulaire">S'enregistrer</button>
                <button type="submit" class="btnSecond espace" href="../../index.php" name="quitter" title="retour vers la page d'acceuil">Annuler</button>
            </div>

        </form>
    </div>
</body>

</html>