<?php
require_once 'connection.php';
function getPublishers(): array
{
    try {

        $con = getConnection();

        $stmt = $con->prepare("SELECT * FROM publishers ORDER BY publisher_id");

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } finally {
        $con = null;
        $stmt = null;
    }
}

function getPublisherById(int $id): array
{
    try {

        $con = getConnection();

        $stmt = $con->prepare("SELECT * FROM publishers WHERE publisher_id =?");
        $stmt->bindValue(1, $id);

        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        return $fila;
    } finally {
        $con = null;
        $stmt = null;
    }
}

function showPublishers(array $data)
{
    if ($data) {
        echo "<table class='table'><tr><th>Id</th> <th> Nombre </th><th></th> </tr> <tbody>";

        foreach ($data as $fila) {
            $id = htmlspecialchars($fila["publisher_id"], ENT_QUOTES | ENT_HTML5, "UTF-8");
            $nombre = htmlspecialchars($fila["name"], ENT_QUOTES | ENT_HTML5, "UTF-8");
            echo "<tr> <td> {$id}</td> <td>{$nombre} </td>";

            echo '<td> <form action="edit_editor.php" method="GET" class="d-inline">';

            echo "<input type=\"hidden\" name=\"id\" value=\"{$id}\">";

            echo '<button type="submit" class="btn btn-primary mx-1">Editar</button>';

            echo '</form>';

            echo '<form action="index.php" method="POST" class="d-inline" onsubmit="return confirm(\'¿Eliminar esta editorial con id '.$id.' ?\');">';

            echo "<input type=\"hidden\" name=\"id\" value=\"{$id}\">";

            echo '<button type="submit" name="accion" value="borrar" class="btn btn-danger">Eliminar</button>';

            echo '</form>';

            echo '</td> </tr>';
        }
        echo "</tbody></table>";
    } else {
        showMsg("No se encontraron registros", "primary");
    }
}

function showMsg(string $msg, string $claseCSS)
{
    echo "<div class=\"alert alert-$claseCSS\" role=\"alert\">
  $msg
</div>
";
}

function insertPublisher(string $nombre): bool|string
{
    try {
        $con = getConnection();
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO publishers(name) VALUES (?)");
        $stmt->bindParam(1, $nombre);
        $stmt->execute();
        $id = $con->lastInsertId();
        $con->commit();
        return $id;
    } catch (PDOException $e) {
        $con->rollBack();
        error_log("Ha ocurrido una excepción insertando en publishers: " . $e->getMessage());
        throw $e;
    } finally {
        $con = null;
        $stmt = null;
    }
}


function deletePublisher(int $id): bool
{
    try {
        $con = getConnection();
        $con->beginTransaction();
        $stmt = $con->prepare("DELETE FROM publishers WHERE publisher_id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $con->commit();
      
    } catch (PDOException $e) {
        $con->rollBack();
        error_log("Ha ocurrido una excepción insertando en publishers: " . $e->getMessage());
        throw $e;
    } finally {
        $con = null;
        $stmt = null;
    }
}

function updatePublisher(int $id, string $new_name): bool
{
    try {
        $con = getConnection();
      
        $stmt = $con->prepare("UPDATE publishers SET name= ? WHERE publisher_id = ?");
        $stmt->bindParam(1, $new_name);
        $stmt->bindValue(2, $id);

        return $stmt->execute();       
         
      
    } catch (PDOException $e) {
       
        error_log("Ha ocurrido una excepción insertando en publishers: " . $e->getMessage());
        throw $e;
    } finally {
        $con = null;
        $stmt = null;
    }
}