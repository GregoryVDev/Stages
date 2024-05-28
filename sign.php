<?php

// Fonction de validation des entrées
function validateEmail($email)
{
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

// On vérifie si le formulaire a été envoyé
if (!empty($_POST)) {
    // Le formulaire a été envoyé
    // On vérifie que tous les champs requis sont remplis
    if (
        isset($_POST["nickname"], $_POST["email"], $_POST["pass"]) && !empty($_POST['nickname']) && !empty($_POST['email']) && !empty($_POST['pass'])
    ) {
        // Formulaire complet
        // On récupère les données en les protégeant
        $pseudo = strip_tags($_POST['nickname']);

        if (!validateEmail($_POST["email"])) {
            die("Invalid email");
        }

        // On va hasher le mot de passe
        $pass = password_hash($_POST["pass"], PASSWORD_ARGON2ID);

        // On enregistre la bdd
        require_once("connect.php");

        $sql = "INSERT INTO users (username, email, pass, roles) VALUES (:pseudo, :email, :pass, '[\"ROLE_USER\"]')";

        $query = $db->prepare($sql);

        $query->bindValue(":pseudo", $pseudo);
        $query->bindValue(":email", $_POST["email"]);

        $query->execute();


        // On récupère l'id du nouvel utilisateur
        $id = $db->lastInsertId();

        // On connectera l'utilisateur

        // On démarre la session PHP
        session_start();

        // On stocke dans $_SESSION les informations de l'utilisateur
        $_SESSION['user'] = [
            "id" => $id,
            "pseudo" => $pseudo,
            "email" => $_POST['email'],
            "roles" => ['ROLE_USER']
        ];

        // Rediriger vers la page index (exemple)
        header("Location: index.php");
    } else {
        // Formulaire incomplet
        die("The form is incomplete");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
</head>

<body>
    <h1>Sign up</h1>

    <form method="post">
        <div>
            <label for="pseudo">Pseudo</label>
            <input type="text" name="nickname" id="pseudo">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="pass">Password</label>
            <input type="password" name="pass" id="pass">
        </div>
        <button type="submit">SIGN UP</button>
        <button><a href="connexion.php" style="text-decoration: none">SIGN IN</a></button>
    </form>

</body>

</html>