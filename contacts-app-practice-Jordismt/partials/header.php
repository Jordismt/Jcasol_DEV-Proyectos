<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Aplicación de Contactos'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <header class="mb-4">
            <h1><?php echo isset($header) ? $header : 'Bienvenido a la Aplicación de Contactos'; ?></h1>
            
            <!-- Menú de navegación con clases Bootstrap -->
            <nav class="nav nav-pills mb-3">
                <a class="nav-link" href="contact_list.php">Lista de Contactos</a>
                <a class="nav-link" href="contact_form.php">Formulario de Contactos</a>
                <a class="nav-link" href="import_export.php">Importar/Exportar</a>
            </nav>
            
            <!-- Mostrar el mensaje de idioma si la cookie está establecida -->
            <?php
            if (isset($_COOKIE['language'])) {
                echo "<div class='alert alert-info'>";
                switch ($_COOKIE['language']) {
                    case 'es':
                        echo "<p>Tu idioma es Español</p>";
                        break;
                    case 'en':
                        echo "<p>Your language is English</p>";
                        break;
                    case 'fr':
                        echo "<p>Votre langue est le Français</p>";
                        break;
                    case 'de':
                        echo "<p>Ihre Sprache ist Deutsch</p>";
                        break;
                }
                echo "</div>";
            }
            ?>
        </header>
