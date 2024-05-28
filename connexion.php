<?php

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// On vérifie si le formulaire a été envoyé
if (!empty($_POST)) {
    // Le formulaire a été envoyé
    // On vérifie que TOUS les champs requis sont remplis

    if (isset($_POST["email"], $_POST["pass"]) && !empty($_POST["email"]) && !empty($_POST["pass"])) {
        // On vérifie que l'email en est un
        if (!validateEmail($_POST["email"])) {
            die("This is not a valid email");
        }

        // On se connecte à la bdd
        require_once("connect.php");

        // Assuming $db is the PDO instance from "connect.php"
        $sql = "SELECT * FROM users WHERE email = :email";

        $query = $db->prepare($sql);

        $query->bindValue(":email", $_POST["email"]);

        $query->execute();

        $user = $query->fetch();

        if (!$user) {
            die("The user and/or password is incorrect");
        }

        // On a un user existant, on peut vérifier son mdp

        if (!password_verify($_POST['pass'], $user["pass"])) {
            die("The user and/or password is incorrect");
        }

        // L'utilisateur et le mot de passe sont corrects
        // On va pouvoir "connecter" l'utilisateur
        // On démarre la session PHP
        session_start();

        // On stocke dans $_SESSION les informations de l'utilisateur
        $_SESSION['user'] = [
            "id" => $user['id'],
            "pseudo" => $user['username'],
            "email" => $user['email'],
            "roles" => $user['roles']
        ];

        // Rediriger vers la page index (exemple)
        header("Location: index.php");
    } else {
        // Formulaire incomplet
        die("Le formulaire est incomplet");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
</head>

<body>

    <h1>Sign in</h1>

    <form method="post">
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="pass">Password</label>
            <input type="password" name="pass" id="pass">
        </div>
        <button type="submit">Sign in</button>
    </form>

</body>

</html>