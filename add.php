<?php

$dsn = "mysql:host=localhost; dbname=literie3000";
$db = new PDO($dsn, "root", "");
// on récupère les marques
$brands = $db->query("SELECT * FROM brands order by name")->fetchAll(PDO::FETCH_ASSOC);
// on récupère les tailles
$sizes = $db->query("SELECT * FROM sizes order by id")->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
    // si le formulaire est rempli on récupère les infos
    $name = trim(strip_tags($_POST["name"]));
    $id_brand = trim(strip_tags($_POST["id_brand"]));
    $id_size = trim(strip_tags($_POST["id_size"]));
    $price = trim(strip_tags($_POST["price"]));
    $saleprice = trim(strip_tags($_POST["saleprice"]));

    //initialisation tableau d'erreurs pr générer différents messages
    $errors = [];

    // vérif champ "name" est renseigné
    if (empty($name)) {
        $errors["name"] = "Veuillez indiquer un nom pour ce modèle";
    }

    // gestion de l'upload photo recette
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] === UPLOAD_ERR_OK) {
        // le fichier avec l'attribut name="picture" existe, et il n'y a pas eu d'erreur à l'upload
        $fileTmpPath = $_FILES["picture"]["tmp_name"];
        $fileName = $_FILES["picture"]["name"];
        $fileType = $_FILES["picture"]["type"];

        // récup de l'extension du fichier via explode + end() qui reprend le dernier élément du tableau
        $fileNameArray = explode(".", $fileName);
        $fileExtension = end($fileNameArray);
        // génération d'un nouveau nom de fichier :
        // md5() évite les caractères non désirés en cryptant, et time()génère un timestamp, donc permet d'avoir un hash unique même si 2pers uploadent des images avec le même nom
        $newFileName = md5($fileName . time()) . "." . $fileExtension;

        // dossier de destination
        $fileDestPath = "./img/catalog/{$newFileName}";

        // vérif que le fichier est bien une image
        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {
            // renvoie vrai si le type de fichier a été trouvé dans le tableau
            // on déplace le fichier du dossier temporaire vers le dossier de notre serveur
            move_uploaded_file($fileTmpPath, $fileDestPath);
        } else {
            $errors["picture"] = "Seuls les formats .jpg, .png ou .webp sont acceptés.";
        }
    }

    // gestion erreurs de prix
    if ($price <= 0) {
        $errors["price"] = "Le prix ne peut pas être inférieur ou égal à 0.";
    }
    if ($saleprice <= 0 || $saleprice >= $price) {
        $errors["saleprice"] = "Le prix réduit ne peut être inférieur à 0, ni supérieur ou égal au prix d'origine.";
    }

    // si le tableau d'erreurs est vide, on lance la requête d'insertion en BDD
    if (empty($errors)) {
        $query = $db->prepare("INSERT INTO mattresses(name, picture, id_brand, id_size, price, saleprice)
        VALUES(:name, :picture, :id_brand, :id_size, :price, :saleprice)");
        $query->bindParam(":name", $name); //PDO valeur par défaut = chaîne de caractère, donc pas de 3eme paramètre
        $query->bindParam(":picture", $newFileName);
        $query->bindParam(":id_brand", $id_brand, PDO::PARAM_INT);
        $query->bindParam(":id_size", $id_size, PDO::PARAM_INT);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":saleprice", $saleprice, PDO::PARAM_INT);

        if ($query->execute()) {
            // on redirige vers la page d'accueil
            header("Location: index.php");
        }
    }
}

include("templates/header.php");
?>

<h2>Ajouter un matelas</h2>

<form action="" method="post" enctype="multipart/form-data">
    <!-- un formulaire utilisant un champ de type FILE doit forcément avoir un attribut ENCTYPE avec la valeur multipart/form-data -->

    <!-- nom du matelas -->
    <div class="form-group">
        <label for="inputName">Nom du modèle :</label>
        <input type="text" id="inputName" name="name" value="<?= isset($name) ? $name : "" ?>">
        <?php
        // si la clé "name" existe dans le tableau d'erreurs
        if (isset($errors["name"])) {
        ?>
            <span class="info-error"><?= $errors["name"] ?></span>
        <?php
        }
        ?>
    </div>

    <!-- marque -->
    <div class="form-group">
        <label for="selectBrand">Marque :</label>
        <select name="id_brand" id="selectBrand">
            <?php foreach ($brands as $brand) {
            ?>
                <option <?= isset($id_brand) && ($id_brand === $brand["id"]) ? "selected" : "" ?> value=<?= $brand["id"] ?>><?= $brand["name"] ?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <!-- taille -->
    <div class="form-group">
        <label for="selectSize">Taille :</label>
        <select name="id_size" id="selectSize">
            <?php foreach ($sizes as $size) {
            ?>
                <option <?= isset($id_size) && ($id_size === $size["id"]) ? "selected" : "" ?> value=<?= $size["id"] ?>><?= $size["size"] ?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <!-- image du matelas -->
    <div class="form-group">
        <label for="inputPicture">Photo :</label>
        <input type="file" id="inputPicture" name="picture">
        <?php
        if (isset($errors["picture"])) {
        ?>
            <span class="info-error"><?= $errors["picture"] ?></span>
        <?php
        }
        ?>
    </div>

    <!-- prix du matelas -->
    <div class="form-group">
        <label for="inputPrice">Prix :</label>
        <input type="number" name="price" id="inputPrice" value="<?= isset($price) ? $price : 0 ?>" min="0">
        <?php
        if (isset($errors["price"])) {
        ?>
            <span class="info-error"><?= $errors["price"] ?></span>
        <?php
        }
        ?>
    </div>

    <!-- prix réduit du matelas -->
    <div class="form-group">
        <label for="inputSaleprice">Prix réduit :</label>
        <input type="number" name="saleprice" id="inputSaleprice" value="<?= isset($saleprice) ? $saleprice : 0 ?>" min="0">
        <?php
        if (isset($errors["saleprice"])) {
        ?>
            <span class="info-error"><?= $errors["saleprice"] ?></span>
        <?php
        }
        ?>
    </div>

    <div class="form-group">
        <a href="index.php">Annuler</a>
        <input type="submit" value="Valider" class="btn">
    </div>
</form>

<?php
include("templates/footer.php");
?>