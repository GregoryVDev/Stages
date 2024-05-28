<?php

if (isset($_GET["id"]) && !empty($_GET['id'])) {

    require_once("connect.php");

    $id = strip_tags($_GET['id']);

    $sql = "SELECT * FROM stage WHERE id=:id";
    $query = $db->prepare($sql);

    $query->bindValue(':id', $id);
    $query->execute();

    $result = $query->fetch();

    if (!$result) {
        header("Location: index.php");
    }

    $sql = "DELETE FROM stage WHERE id=:id";
    $query = $db->prepare($sql);

    $query->bindValue(":id", $id);
    $query->execute();

    require_once("close.php");
    header("Location: index.php");

    session_start();

    $_SESSION['delete_confirm'] = true;
    $_SESSION['stage_delete_id'] = $id;
    $_SESSION['stage_name'] = $result[2];
} else {
    header("Location: index.php");
}
