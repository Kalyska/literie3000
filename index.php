<?php
// récupération des données
$dsn = "mysql:host=localhost; dbname=literie3000";
$db = new PDO($dsn, "root", "");
$mattresses = $db->query("SELECT mattresses.*, brands.name as brand, size
FROM mattresses
INNER JOIN brands on brands.id = id_brand
INNER JOIN sizes on id_size = sizes.id
order by mattresses.id")->fetchAll(PDO::FETCH_ASSOC);

// Inclure le template header
include("templates/header.php");
?>
<h2>Nos matelas</h2>
<div class="mattresses">
    <?php
    foreach ($mattresses as $mattress) {
    ?>
        <div class="mattress">
            <!-- <img src="img/catalog/<?= $mattress["picture"] ?>" alt=""> -->
            <h3>
                <a href="mattress.php?id=<?= $mattress["id"] ?>"><?= $mattress["name"] ?></a>
            </h3>
            <p>Marque : <?= $mattress["brand"] ?></p>
            <p>Tailles disponibles : <?= $mattress["size"] ?></p>
        </div>
    <?php
    }
    ?>
</div>
<?php
// Inclure le template footer
include("templates/footer.php");
?>