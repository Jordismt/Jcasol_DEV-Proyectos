<?php
require_once 'ContactRepository.php'; // Incluimos el repositorio de contactos
require_once 'Contact.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener todos los contactos desde la base de datos
$contacts = ContactRepository::getAll();

?>

<?php include 'partials/header.php'; ?>

<div class="container mt-5">
    <h2>Lista de Contactos</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contact->getId()); ?></td>
                    <td><?php echo htmlspecialchars($contact->getTitle()); ?></td>
                    <td><?php echo htmlspecialchars($contact->getName()); ?></td>
                    <td><?php echo htmlspecialchars($contact->getSurname()); ?></td>
                    <td><?php echo htmlspecialchars($contact->getPhone()); ?></td>
                    <td><?php echo htmlspecialchars($contact->getEmail()); ?></td>
                    <td>
                        <a href="contact_form.php?id=<?php echo $contact->getId(); ?>" class="btn btn-warning">Editar</a>
                        <a href="delete_contact.php?id=<?php echo $contact->getId(); ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="contact_form.php" class="btn btn-primary">Agregar Contacto</a>
</div>

<?php include 'partials/footer.php'; ?>
