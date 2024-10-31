<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'ContactRepository.php';
include 'partials/header.php';

// Exportar contactos a JSON
if (isset($_POST['export_json'])) {
    ContactRepository::exportContactsToJSON();
}

// Importar contactos desde un archivo JSON
if (isset($_POST['import_json']) && isset($_FILES['json_file'])) {
    $fileTmpPath = $_FILES['json_file']['tmp_name'];
    ContactRepository::importContactsFromJSON($fileTmpPath);
    echo "<div class='alert alert-success'>Contactos importados exitosamente.</div>";
}

// Guardar idioma en una cookie
if (isset($_POST['set_language'])) {
    $selectedLanguage = $_POST['language'];
    setcookie('language', $selectedLanguage, time() + (86400 * 10), "/");
    echo "<div class='alert alert-info'>Idioma guardado exitosamente.</div>";
}
?>



<div class="container mt-5">
    <h2>Importar y Exportar Contactos</h2>

    <!-- Formulario para exportar contactos -->
    <form action="import_export.php" method="POST">
        <button type="submit" name="export_json" class="btn btn-primary">Exportar contactos a JSON</button>
    </form>

    <!-- Formulario para importar contactos -->
    <form action="import_export.php" method="POST" enctype="multipart/form-data" class="mt-3">
        <label for="json_file">Subir archivo JSON:</label>
        <input type="file" name="json_file" id="json_file" accept=".json" required>
        <button type="submit" name="import_json" class="btn btn-secondary">Importar contactos desde JSON</button>
    </form>

    <!-- Seleccionar idioma -->
    <form action="import_export.php" method="POST" class="mt-3">
        <label for="language">Seleccionar idioma:</label>
        <select name="language" id="language" class="form-control" style="width: 200px;">
            <option value="es">Español</option>
            <option value="en">Inglés</option>
            <option value="fr">Francés</option>
            <option value="de">Alemán</option>
        </select>
        <button type="submit" name="set_language" class="btn btn-info mt-2">Guardar idioma</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
