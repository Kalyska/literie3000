<?php
// récupération des données
$dsn = "mysql:host=localhost; dbname=literie3000";
$db = new PDO($dsn, "root", "");
$mattresses = $db->query("SELECT mattresses.*, brands.name as brand, size
FROM mattresses
INNER JOIN brands on brands.id = id_brand
INNER JOIN sizes on id_size = sizes.id
order by mattresses.name")->fetchAll(PDO::FETCH_ASSOC);

// Inclure le template header
include("templates/header.php");
?>
<h2>Nos matelas</h2>
<div class="mattresses">
    <?php
    foreach ($mattresses as $mattress) {
    ?>
        <hr>
        <div class="mattress">
            <img class="catalog-img" src="img/catalog/<?= $mattress["picture"] ?>" alt="">

            <div class="details">
                <h3><?= $mattress["name"] ?></h3>

                <p>Marque : <?= $mattress["brand"] ?></p>
                <p>Taille : <?= $mattress["size"] ?></p>

                <div class="actions">
                    <a href="edit.php?id=<?= $mattress["id"] ?>"><i class="fa-regular fa-pen-to-square edit"></i></a>
                    <a href="delete.php?id=<?= $mattress["id"] ?>"><i class="fa-solid fa-trash warning"></i></a>
                </div>
            </div>

            <div class="prices">
                <p class="high"><?= $mattress["price"] ?> €</p>
                <p class="low"><?= $mattress["saleprice"] ?> €</p>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php
// Inclure le template footer
include("templates/footer.php");
?>