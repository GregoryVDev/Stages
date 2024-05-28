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
        $query->bindValue(":pass", $pass);

        $query->execute();
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
        <button type="submit">Sign up</button>
    </form>
</body>

</html>