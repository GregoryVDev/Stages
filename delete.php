<?php

session_start();

if (!isset($_SESSION['user'])) {
    // On renvoie la personne vers la page de connexion
    header("Location: connexion.php");
    exit();
}

if (isset($_GET["id"]) && !empty($_GET['id'])) {

    require_once("connect.php");

    $id = strip_tags($_GET['id']);

    // On récupère l'id de l'utilisateur connecté
    $user_id = $_SESSION['user']['id'];


    // On vérifie si le stage appartient bien à l'utilisateur connecté
    $sql = "SELECT * FROM stage WHERE id = :id AND user_id = :user_id";
    $query = $db->prepare($sql);

    $query->bindValue(':id', $id);
    $query->bindValue(':user_id', $user_id);
    $query->execute();

    $result = $query->fetch();

    if (!$result) {
        header("Location: index.php");
    }

    // On vérifie si le stage existe et si il appartient à l'utilisateur connecté, il le supprime

    $sql = "DELETE FROM stage WHERE id = :id AND user_id = :user_id";
    $query = $db->prepare($sql);

    $query->bindValue(":id", $id);
    $query->bindValue(":user_id", $user_id);
    $query->execute();

    require_once("close.php");
    header("Location: index.php");

    $_SESSION['delete_confirm'] = true;
    $_SESSION['stage_delete_id'] = $id;
    $_SESSION['stage_name'] = $result[2];
} else {
    header("Location: index.php");
}
