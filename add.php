<?php

session_start();

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

        $status = strip_tags($_POST['status']);
        $name = strip_tags($_POST['name']);
        $apply = strip_tags($_POST['apply']);
        $type = strip_tags($_POST['type']);
        $method = strip_tags($_POST['method']);
        $position = strip_tags($_POST['position']);
        $contrat = strip_tags($_POST['contrat']);
        $email = strip_tags($_POST['email']);
        $commentary = strip_tags($_POST['commentary']);

        // Vérifier si la date est vide
        if (!empty($_POST['dunning_date'])) {
            // Vérifier aussi si la date est valide
            $timestamp = strtotime($_POST['dunning_date']);
            if ($timestamp !== false) {
                // si la date est valide alors il met la date
                $dunning_date = date('Y-m-d', $timestamp);
            } else {
                // sinon on arrête l'éxecution du script
                exit();
            }
        } else {
            // Sinon si la date est vide, alors la date sera définie comme null
            $dunning_date = null;
        }

        $sql = "INSERT INTO stage (status ,name, apply, dunning_date, type, method, position, contrat, email, commentary) VALUES (:status, :name, :apply, :dunning_date, :type, :method, :position, :contrat, :email, :commentary)";
        $query = $db->prepare($sql);

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

        $query->execute();

        require_once("close.php");

        header("Location: index.php");
        exit();
    }
}
?>



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Add internship</title>
</head>

<body>

    <h1>Add internship</h1>

    <form method="post" class="form">

        <label for="status">Status</label>

        <select name="status" id="action-select" required>
            <option value="">--Please choose an option--</option>
            <option value="applied">Applied</option>
            <option value="not-conrrespond">Does not correspond</option>
            <option value="interview">Job interview</option>
            <option value="offer">Job offer</option>
            <option value="refusal">Refusal</option>
            <option value="hiring">Hiring</option>
            <option value="no-answer">No answer</option>
            <option value="relaunched">Relaunched</option>
        </select>

        <label for="name">Name</label>
        <input type="text" name="name" required>

        <label for="apply">Apply</label>
        <input type="date" name="apply" required>

        <label for="dunning_date">Dunning Date</label>
        <input type="date" name="dunning_date">

        <label for="type">Type</label>
        <select name="type" id="type-select" required>
            <option value="">--Please choose an option--</option>
            <option value="spontaneous">Spontaneous</option>
            <option value="response-offer">Response to an offer</option>
            <option value="recommendation">Recommendation</option>
            <option value="solicitation">Direct solicitation</option>
        </select>


        <label for="method">Method</label>
        <select name="method" id="method" required>
            <option value="">--Please choose an option--</option>
            <option value="person">In person</option>
            <option value="email">Email</option>
            <option value="linkedln">Linkedln</option>
            <option value="job-board">Job Board</option>
            <option value="webside">Webside</option>
            <option value="recommendation">Recommendation</option>
            <option value="solicitation">Direct Solicitation</option>

        </select>

        <label for="position">Position</label>
        <input type="text" name="position" required>

        <label for="contrat">Contrat</label>
        <select name="contrat" id="contrat" required>
            <option value="">--Please choose an option--</option>
            <option value="internship">Internship</option>
            <option value="fixed-term">Fixed-term contrat</option>
            <option value="indefinite-term">Indefinite-term contrat</option>
            <option value="freelance">Freelance</option>
        </select>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="commentary">Commentary</label>
        <input type="text" name="commentary" required>
        <input type="submit" value="SEND"></input>

    </form>
    <a href="index.php" style="margin-top: 50px;">Back</a>
</body>

</html>