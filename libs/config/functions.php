<?php 
// fonction qui détermine à partir de l'email renseigné si un utilisateur est connu dans ce cas il renvoi ses infos sous forme d'un tableau associatif.
function getUser(string $emailUser) {
  // Préparation de la requête = statement
  global $pdo;
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

  // Injection de paramètres
  $stmt->bindValue(":email", $emailUser, PDO::PARAM_STR);

  // Exécution
  $stmt->execute();

  // Récupération de l'user
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    return $user;
  }
};


// Message d'erreur si utilisateur non connecté tente d'accéder aux pages nécessitant une authentification
function accessDenied(){
    return die("<div style='text-align: center; position: absolute;left: 50%;top: 50%;transform:translate(-50%,-50%);'> <span style='font-size:100px;'> &#x26d4;</span> <p> Accés refusé ! <br> <br> veuillez <a href=\"../security/login.php\">vous connectez</a> ou <a href=\"../security/register.php\">enregistrez vous</a>  <br> <br> pour utiliser cette application</p></div>");
}
