<?php

session_start();

if (
    isset($_GET['id']) && !empty($_GET['id'])

) {
    require_once("connect.php");

    $id = strip_tags($_GET['id']);

    $user_id = $_SESSION['user']['id'];

    $sql = "SELECT * FROM stage WHERE id=:id AND user_id=:user_id";
    $query = $db->prepare($sql);

    $query->bindValue(":id", $id,);
    $query->bindValue(":user_id", $user_id);

    $query->execute();

    $result = $query->fetch();

    if (!$result) {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Internship Profile</title>
</head>

<body>

    <div class="container">
        <h1>Internship Profile</h1>
        <h2><?= ($result['name']) ?></h2>

        <table>
            <tr>
                <th>Status</th>
                <td><?= ($result['status']) ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?= ($result['name']) ?></td>
            </tr>
            <tr>
                <th>Apply</th>
                <td><?= ($result['apply']) ?></td>
            </tr>
            <tr>
                <th>Dunning Date</th>
                <td><?= ($result['dunning_date']) ?></td>
            </tr>
            <tr>
                <th>Type</th>
                <td><?= ($result['type']) ?></td>
            </tr>
            <tr>
                <th>Method</th>
                <td><?= ($result['method']) ?></td>
            </tr>
            <tr>
                <th>Position</th>
                <td><?= ($result['position']) ?></td>
            </tr>
            <tr>
                <th>Contrat</th>
                <td><?= ($result['contrat']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= ($result['email']) ?></td>
            </tr>
            <tr>
                <th>Commentary</th>
                <td><?= ($result['commentary']) ?></td>
            </tr>
        </table>
    </div>

    <a href="index.php">BACK</a>

</body>

</html>