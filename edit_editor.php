<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear editor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<?php
require_once 'util.php';
$name = "";
$id = 0;
$accion = "Crear";
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = $_GET["id"] ?? 0;
    if ($id > 0) {
        $accion = "Editar";
        $fila = getPublisherById($id);
        if (!$fila) {
            showMsg("No se encontró la editorial con id $id", "danger");
        } else {


            $name = $fila["name"];



        }
    }
}

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? 0;
    $nombre = $_POST["nombre"] ?? "";
    if (trim($nombre) !== "") {
        if ($id > 0) {
            //edición
            vwUpdate($id, $nombre);
        } else {
            //creación        
            vwInsert($nombre);
        }
    } else {
        showMsg("El nombre no puede estar vacío", "primary");
    }
}
?>

<body>
    <div class="container">
        <h1> <?= $accion ?> editor</h1>
        <a href="index.php">Volver al listado</a>

        <form method="post">
            <label for="nombre" class="form-label"></label>
            <input id="nombre" type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, "UTF-8"); ?>" required>
            <?php
            if ($id > 0) {
                echo "<input type='hidden' name='id' value='$id'/>";
            }
            ?>

            <button name="accion" class="btn btn-primary mt-3 mb-3" value="<?= $accion ?>"><?= $accion ?> </button>
        </form>

        <?php




        function vwInsert(string $nombre)
        {
            try {
                if (($id = insertPublisher($nombre)) !== false) {
                    showMsg("Se ha creado una nueva editorial con id $id", "success");
                } else {
                    showMsg("Ha ocurrido un error y no ha podido crearse el registro", "danger");
                }
            } catch (Exception $e) {
                showMsg("Ha ocurrido un error inesperado", "danger");
            }
        }

        function vwUpdate(int $id, string $nombre)
        {
            try {
                if (updatePublisher($id, $nombre) !== false) {
                    showMsg("Se ha modificado con éxito.", "success");
                } else {
                    showMsg("Ha ocurrido un error y no ha podido modificarse el registro", "danger");
                }
            } catch (Exception $e) {
                showMsg("Ha ocurrido un error inesperado", "danger");
            }
        }
        ?>
    </div>
</body>

</html>