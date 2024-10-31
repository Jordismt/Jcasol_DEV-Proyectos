<?php
require_once 'ContactRepository.php';
require_once 'Contact.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar variables para almacenar los datos del contacto
$id = '';
$title = 'Mr.';
$name = '';
$surname = '';
$birthdate = '';
$phone = '';
$email = '';
$favourite = false;
$important = false;
$archived = false;
$birthdayMessage = '';

$contact = null;

// Verificar si se está editando un contacto
if (isset($_GET['id'])) {
    $contactId = (int)$_GET['id'];
    $contact = ContactRepository::select($contactId);

    if ($contact) {
        $id = $contact->getId();
        $title = $contact->getTitle();
        $name = $contact->getName();
        $surname = $contact->getSurname();
        $birthdate = $contact->getBirthdate();
        $phone = $contact->getPhone();
        $email = $contact->getEmail();
        $favourite = $contact->isFavourite();
        $important = $contact->isImportant();
        $archived = $contact->isArchived();
    }
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['id'] = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $contact = new Contact($_POST);
    $errors = $contact->validate();

    if (empty($errors)) {
        if (!empty($_POST['id'])) {
            // Actualizar contacto existente
            ContactRepository::update($contact);
        } else {
            // Insertar nuevo contacto
            ContactRepository::insert($contact);
        }

        $daysUntilBirthday = $contact->checkBirthday();
        if ($daysUntilBirthday === 0) {
            $birthdayMessage = "<p>¡Hoy es el cumpleaños de " . htmlspecialchars($contact->getName()) . "!</p>";
        } elseif ($daysUntilBirthday > 0 && $daysUntilBirthday <= 7) {
            $birthdayMessage = "<p>El cumpleaños de " . htmlspecialchars($contact->getName()) . " es en $daysUntilBirthday días.</p>";
        } else {
            $birthdayMessage = "<p>El cumpleaños de " . htmlspecialchars($contact->getName()) . " es en más de una semana.</p>";
        }

        $_SESSION['birthdayMessage'] = $birthdayMessage;

        // Mensaje de éxito
        $_SESSION['success'] = "Contacto guardado exitosamente.";

        
    } else {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
    }
}
?>

<?php include 'partials/header.php'; // Incluir encabezado ?>

<div class="container mt-5">
    <h2>Formulario de Contacto</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>ID</label>
            <input type="text" name="id" value="<?php echo htmlspecialchars($id); ?>" class="form-control" readonly> <!-- Campo ID, solo lectura -->
        </div>
        <div class="form-group">
            <label>Título</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" value="Mr." <?php echo ($title == 'Mr.') ? 'checked' : ''; ?>>
                <label class="form-check-label">Sr.</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" value="Mrs." <?php echo ($title == 'Mrs.') ? 'checked' : ''; ?>>
                <label class="form-check-label">Sra.</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="title" value="Miss" <?php echo ($title == 'Miss') ? 'checked' : ''; ?>>
                <label class="form-check-label">Srta.</label>
            </div>
        </div>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control"> <!-- Campo para el nombre -->
        </div>
        <div class="form-group">
            <label>Apellido</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($surname); ?>" class="form-control"> <!-- Campo para el apellido -->
        </div>
        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" class="form-control"> <!-- Campo para la fecha de nacimiento -->
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="form-control"> <!-- Campo para el teléfono -->
        </div>
        <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control"> <!-- Campo para el correo electrónico -->
        </div>
        <div class="form-group">
            <label>Tipo</label><br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="favourite" value="1" <?php echo $favourite ? 'checked' : ''; ?>> <!-- Opción para marcar como favorito -->
                <label class="form-check-label">Favorito</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="important" value="1" <?php echo $important ? 'checked' : ''; ?>> <!-- Opción para marcar como importante -->
                <label class="form-check-label">Importante</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="archived" value="1" <?php echo $archived ? 'checked' : ''; ?>> <!-- Opción para marcar como archivado -->
                <label class="form-check-label">Archivado</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo empty($id) ? 'Guardar' : 'Actualizar'; ?></button> <!-- Botón para guardar o actualizar -->
        <a href="contact_list.php" class="btn btn-secondary">Volver a la lista</a> <!-- Enlace para volver a la lista de contactos -->
    </form>

    <!-- Mostrar el mensaje del cumpleaños si existe -->
    <?php if (!empty($birthdayMessage)): ?>
        <div class="alert alert-info mt-4">
            <?php echo $birthdayMessage; ?> <!-- Mensaje de cumpleaños -->
        </div>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; // Incluir pie de página ?>
