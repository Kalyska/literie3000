<?php
$dsn = "mysql:host=localhost; dbname=literie3000";
$db = new PDO($dsn, "root", "");

// récupération du matelas en fonction de l'id
if (isset($_GET["id"])) {
    $id = $_GET['id'];
    $query = $db->prepare('SELECT * FROM mattresses WHERE id = :id');
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $mattress = $query->fetch();

    if (isset($_GET["confirm"]) && $_GET["confirm"] === 'true') {
        $query = $db->query("DELETE from mattresses WHERE id = $id");
        header("Location: index.php");
    }
} else {
    // si pas d'id on redirige vers l'accueil
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literie 3000</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <div class="container delete">
        <h2>Suppression du catalogue</h2>
        <p>Vous êtes sur le point de supprimer <?= $mattress["name"] ?> du catalogue.</p>
        <div class="btn"><a href="/literie3000">Annuler</a> <a href="/literie3000/delete.php?id=<?= $mattress["id"] ?>&confirm=true">Valider</a></div>

    </div>
</body>

</html>