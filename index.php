<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A3.2 Listado de editoriales</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Listado de editoriales</h1>

        <a href="edit_editor.php">Crear editorial</a>

        <?php
        require_once 'util.php';
        if (isset($_POST["accion"])) {
            $accion = $_POST["accion"];
            if ($accion == "borrar") {
                $id = $_POST["id"] ?? 0;
                if ($id > 0) {
                    try {
                        $eliminado = deletePublisher($id);
                        if($eliminado){
                            showMsg("Se ha eliminado la editorial correctamente", "success");
                        }
                        else{
                            showMsg("Ha ocurrido un error y no se ha  podido eliminar la editorial", "danger");
                        }
                    } catch (Exception $e) {
                        showMsg("Ha ocurrido un error inesperado", "danger");
                    }
                }
            }
        }


        try {
            $data = getPublishers();
            showPublishers($data);
        } catch (Exception $e) {
            error_log("Ha ocurrido una excepción: " . $e->getMessage());
            showMsg("Ha ocurrido un error inesperado", "danger");
        }
        ?>
    </div>
</body>

</html>