<?php
session_start();

require "../config/pdo.php";
require "../config/functions.php";

if (isset($_POST['quitter']) || isset($_SESSION["user_id"])) {
    header('location: ../../index.php');
    session_destroy();
    return;
}

if (isset($_POST['valider'])) {
    
    $email = htmlentities($_POST["email"]);
    $password = htmlentities($_POST["password"]);
    if ((isset($email) && !empty($email)) && (isset($password) && !empty($password))) {

        // vérification de la validité de l'email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Vérification si l'utilisateur est déja enregistré
            if (getUser($email)) {
                $user = getUser($email);

                // Vérification de la validité du mot de passe
                if (password_verify($password, $user['password']) ) {

                    // Nous transmettons les données de l'utilisateur à travers la session pour contrôler leur conformité à chaque session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['pseudo'] = $user['pseudo'];

                    $_SESSION['salutation'] = "Bienvenue, Dans Votre Espace Privé <span style='font-size:20px;'>&#128521;</span>";

                    header("location: ../app/app.php"); // Redirection vers la page de liste des tâches
                    return;

                } else {
                        // sauvegarde de l'émail pour une auto complétion si erreur 
                        $_SESSION['email'] = $email;
                        $_SESSION["message"] = "Mot de passe incorrect";
                        header("location: login.php");
                        return;
                    }
            } else {        
                
                // sauvegarde de l'émail pour une auto complétion si erreur 
                $_SESSION['email'] = $email;

                $_SESSION["message"] = " 
                Pour s'enregistrer veuillez suivre ce lien <a class='link-redirection' href='./register.php'>SignUp</a>";
                header("location: login.php");
                return;
            }

        } else {
                $_SESSION["message"] = " 
                Email invalide, pour s'enregistrer veuillez suivre ce lien <a class='link-redirection' href='./register.php'>SignUp</a>";
                header("location: login.php");
                return;
            }
    } else {
            $_SESSION["message"] = "Le nom d'utilisateur et le mot de passe sont requis";
            header("location: login.php");
            return;
        }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="description" content="Connexion à notre Application de Gestion de tâches.">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../main.css">
    <title>Page De Connexion</title>
</head>

<body>
    <div class="container">
        <h1>Se Connecter</h1>
        <div class="underline"></div>

        <div class="containerMessages">

                    <?php if (isset($_SESSION['message'])) {
                        echo  "<div class='error-in-app' role='alert'> {$_SESSION['message']}</div>";
                        unset($_SESSION['message']);
                    } ?>

        </div>

        <form class="form" method="POST" novalidate>
            <div class="form-group">

                <label for="focusInputEmail">Adresse Électronique</label>
                <input type="email" name="email" class="inputData" id="focusInputEmail" aria-describedby="emailHelp"
                    placeholder="Entez votre email" value="<?php if(isset($_SESSION['email']))
                            { echo htmlentities($_SESSION['email']);}?>">
            </div>
            <div class="form-group">
                <label for="focusInputPassW">Mot de Passe</label>
                <input type="password" name="password" class="inputData" id="focusInputPassW" placeholder="Mot de passe">
            </div>
            <div class="container-btns login">

                <button type="submit" class="btnFirst" name="valider" title="Se Connecter">Se Connecter</button>
                <div class="linkQuitter">

                    <a class="btnSecond" href="../../index.php" name="quitter" title="retour vers la page d'acceuil">Quitter</a>
                </div>
            </div>


        </form>
    </div>
</body>

</html>