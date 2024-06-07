<?php

session_start();
require_once("connect.php");

// On vérifie si l'utilisateur est connecté

if (!isset($_SESSION["user"])) {
    header("Location: connexion.php");
    exit;
}

// On récupère l'ID de l'utilisateur si il est connecté

$user_id = $_SESSION["user"]["id"];

// On séléctionne uniquement les stages de l'utilisateur connecté

$sql = "SELECT * FROM stage WHERE user_id = :user_id";

$query = $db->prepare($sql);

$query->bindParam(":user_id", $user_id);

$query->execute();

$result = $query->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Internship</title>
</head>

<body>

    <?php if (isset($_SESSION['delete_confirm']) && $_SESSION['delete_confirm'] === true) : ?>
        <div>
            <p><?= $_SESSION['stage_name'] ?> has been successfully deleted.</p>
        </div>
        <?php unset($_SESSION["delete_confirm"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["update_confirm"]) && $_SESSION["update_confirm"] === "valid" && isset($_SESSION["name_stage"])) : ?>
        <div>
            <p><?= $_SESSION['name_stage'] ?> has been modified.</p>
        </div>
        <?php unset($_SESSION['update_confirm']);
        unset($_SESSION['name_stage']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['name_confirm']) && $_SESSION['name_confirm'] === "confirm" && isset($_SESSION["name_add"])) : ?>
        <div>
            <p><?= ($_SESSION['name_add']) ?> has been added.</p>
        </div>
        <?php unset($_SESSION['name_confirm']);
        unset($_SESSION['name_add']); ?>
    <?php endif; ?>

    <div class="buttons-container">
        <?php if (!isset($_SESSION["user"])) : ?>
            <button><a href="sign.php">SIGN UP</a></button>
            <button><a href="connexion.php">SIGN IN</a></button>
        <?php else : ?>
            <button><a href="disconnect.php">LOGOUT</a></button>
        <?php endif; ?>
    </div>

    <h1>Internship</h1>

    <?php if (isset($_SESSION['user'])) : ?>
        <h2>Connected profile <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></h2>
    <?php endif; ?>



    <table>
        <thead>
            <th>Status</th>
            <th>Name</th>
            <th>Apply</th>
            <th>Dunning Date</th>
            <th>Type</th>
            <th>Method</th>
            <th>Position</th>
            <th>Contrat</th>
            <th>Email</th>
            <th>Commentary</th>
            <th>Action</th>
        </thead>
        <?php if (isset($_SESSION['user'])) : // Ajout de la condition 
        ?>
            <?php foreach ($result as $stage) : ?>
                <tbody>
                    <tr>
                        <td><?= ($stage['status']) ?></td>
                        <td><?= ($stage['name']) ?></td>
                        <td><?= ($stage['apply']) ?></td>
                        <td><?= ($stage['dunning_date']) ?></td>
                        <td><?= ($stage['type']) ?></td>
                        <td><?= ($stage['method']) ?></td>
                        <td><?= ($stage['position']) ?></td>
                        <td><?= ($stage['contrat']) ?></td>
                        <td><?= ($stage['email']) ?></td>
                        <td><?= ($stage['commentary']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $stage["id"] ?>">Edit</a>
                            <a href="details.php?id=<?= $stage["id"] ?>">Profil</a>
                            <a href="delete.php?id=<?= $stage["id"] ?>">Delete</a>
                        </td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
        <?php endif; // Fin de la condition 
        ?>

    </table>
    <?php if (isset($_SESSION['user'])) : // Ajout de la condition 
    ?>
        <a href="add.php">Add</a>
    <?php endif; // Fin de la condition 
    ?>

</body>

</html>