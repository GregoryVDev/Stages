<?php

session_start();
require_once("connect.php");

$sql = "SELECT * FROM stage";

$query = $db->prepare($sql);
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
            <p><?= $_SESSION['name_stage'] ?> has been modified by <?= ($_SESSION['name_edited']) ?>.</p>
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



    <h1>Internship</h1>

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
    </table>
    <a href="add.php">Add</a>

</body>

</html>