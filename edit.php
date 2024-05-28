<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if ($_POST) {
    if (
        isset($_POST['status']) &&
        isset($_POST['name']) &&
        isset($_POST['apply']) &&
        isset($_POST['type']) &&
        isset($_POST['method']) &&
        isset($_POST['position']) &&
        isset($_POST['contrat']) &&
        isset($_POST['email']) &&
        isset($_POST['commentary'])
    ) {
        require_once("connect.php");

        $id = strip_tags($_POST['id']);
        $status = strip_tags($_POST['status']);
        $name = strip_tags($_POST['name']);
        $apply = strip_tags($_POST['apply']);
        $type = strip_tags($_POST['type']);
        $method = strip_tags($_POST['method']);
        $position = strip_tags($_POST['position']);
        $contrat = strip_tags($_POST['contrat']);
        $email = strip_tags($_POST['email']);
        $commentary = strip_tags($_POST['commentary']);

        $user_id = $_SESSION['user']['id'];

        if (!empty($_POST['dunning_date'])) {
            $timestamp = strtotime($_POST['dunning_date']);
            if ($timestamp !== false) {
                $dunning_date = date('Y-m-d', $timestamp);
            } else {
                exit();
            }
        } else {
            $dunning_date = null;
        }

        $sql = "UPDATE stage SET status=:status, name=:name, apply=:apply, dunning_date=:dunning_date, type=:type, method=:method, position=:position, contrat=:contrat, email=:email, commentary=:commentary WHERE id=:id AND user_id=:user_id";
        $query = $db->prepare($sql);

        $query->bindValue(":id", $id);
        $query->bindValue(":status", $status);
        $query->bindValue(":name", $name);
        $query->bindValue(":apply", $apply);
        $query->bindValue(":dunning_date", $dunning_date);
        $query->bindValue(":type", $type);
        $query->bindValue(":method", $method);
        $query->bindValue(":position", $position);
        $query->bindValue(":contrat", $contrat);
        $query->bindValue(":email", $email);
        $query->bindValue(":commentary", $commentary);
        $query->bindValue(':user_id', $user_id);

        $result = $query->execute();

        require_once("close.php");

        if ($result) {
            $_SESSION["name_edited"] = $name;
            header("Location: index.php");
            exit();
        } else {
            echo "Update failed.";
        }
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once("connect.php");

    $id = strip_tags($_GET['id']);
    $user_id = $_SESSION['user']['id'];

    $sql = "SELECT * FROM stage WHERE id = :id AND user_id = :user_id";
    $query = $db->prepare($sql);

    $query->bindValue(':id', $id);
    $query->bindValue(':user_id', $user_id);

    $query->execute();

    $result = $query->fetch();

    if (!$result) {
        header("Location: index.php");
        exit();
    }

    $_SESSION['update_confirm'] = "valid";
    $_SESSION['name_stage'] = $result['name'];
} else {
    header("Location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Internship edit</title>
</head>

<body>
    <h1>Internship edit</h1>

    <form method="post" class="form">

        <label for="status">Status</label>

        <select name="status" id="action-select" required>
            <option value="">--Please choose an option--</option>
            <option value="Applied" <?= $result['status'] == 'Applied' ? 'selected' : '' ?>>Applied</option>
            <option value="Does not correspond" <?= $result['status'] == 'Does not correspond' ? 'selected' : '' ?>>Does not correspond</option>
            <option value="Interview" <?= $result['status'] == 'Interview' ? 'selected' : '' ?>>Job interview</option>
            <option value="Offer" <?= $result['status'] == 'Offer' ? 'selected' : '' ?>>Job offer</option>
            <option value="Refusal" <?= $result['status'] == 'Refusal' ? 'selected' : '' ?>>Refusal</option>
            <option value="Hiring" <?= $result['status'] == 'Hiring' ? 'selected' : '' ?>>Hiring</option>
            <option value="No answer" <?= $result['status'] == 'No answer' ? 'selected' : '' ?>>No answer</option>
            <option value="Relaunched" <?= $result['status'] == 'Relaunched' ? 'selected' : '' ?>>Relaunched</option>
        </select>

        <label for="name">Name</label>
        <input type="text" name="name" value="<?= $result['name'] ?>" required>

        <label for="apply">Apply</label>
        <input type="date" name="apply" value="<?= $result['apply'] ?>" required>

        <label for="dunning_date">Dunning Date</label>
        <input type="date" name="dunning_date" value="<?= $result['dunning_date'] ?>">

        <label for="type" value="<?= $result['type'] ?>">Type</label>
        <select name="type" id="type-select" required>
            <option value="">--Please choose an option--</option>
            <option value="Spontaneous" <?= $result['type'] == 'Spontaneous' ? 'selected' : '' ?>>Spontaneous</option>
            <option value="Response-offer" <?= $result['type'] == 'Response-offer' ? 'selected' : '' ?>>Response to an offer</option>
            <option value="Recommendation" <?= $result['type'] == 'Recommendation' ? 'selected' : '' ?>>Recommendation</option>
            <option value="Solicitation" <?= $result['type'] == 'Solicitation' ? 'selected' : '' ?>>Direct solicitation</option>
        </select>


        <label for="method" value="<?= $result['method'] ?>">Method</label>
        <select name="method" id="method" required>
            <option value="">--Please choose an option--</option>
            <option value="Person" <?= $result['method'] == 'Person' ? 'selected' : '' ?>>In person</option>
            <option value="Email" <?= $result['method'] == 'Email' ? 'selected' : '' ?>>Email</option>
            <option value="lLinkedln" <?= $result['method'] == 'Linkedln' ? 'selected' : '' ?>>Linkedln</option>
            <option value="Job-board" <?= $result['method'] == 'Job-board' ? 'selected' : '' ?>>Job Board</option>
            <option value="Webside" <?= $result['method'] == 'Webside' ? 'selected' : '' ?>>Webside</option>
            <option value="Recommendation" <?= $result['method'] == 'Recommendation' ? 'selected' : '' ?>>Recommendation</option>
            <option value="Solicitation" <?= $result['method'] == 'Solicitation' ? 'selected' : '' ?>>Direct Solicitation</option>

        </select>

        <label for="position">Position</label>
        <input type="text" name="position" value="<?= $result['position'] ?>" required>

        <label for="contrat" value="<?= $result['contrat'] ?>">Contrat</label>
        <select name="contrat" id="contrat" required>
            <option value="">--Please choose an option--</option>
            <option value="Internship" <?= $result['contrat'] == 'Internship' ? 'selected' : '' ?>>Internship</option>
            <option value="Fixed-term" <?= $result['contrat'] == 'Fixed-term' ? 'selected' : '' ?>>Fixed-term contrat</option>
            <option value="Indefinite-term" <?= $result['contrat'] == 'Indefinite-term' ? 'selected' : '' ?>>Indefinite-term contrat</option>
            <option value="Freelance" <?= $result['contrat'] == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
        </select>

        <label for="email">Email</label>
        <input type="email" name="email" value="<?= $result['email'] ?>" required>

        <label for="commentary">Commentary</label>
        <input type="text" name="commentary" value="<?= $result['commentary'] ?>" required>

        <input type="hidden" name="id" value="<?= $result['id'] ?>">
        <input type="submit" value="EDIT"></input>
    </form>

</body>

</html>