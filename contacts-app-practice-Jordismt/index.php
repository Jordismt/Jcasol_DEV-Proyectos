<?php
session_start();
?>
<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
?>

<?php include 'partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Agenda de Contactos</h1>
    <p class="text-center">Selecciona una opción para comenzar:</p>

    <div class="row mt-5 justify-content-center">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Crear Nuevo Contacto</h5>
                    <p class="card-text">Añade un nuevo contacto a tu agenda.</p>
                    <a href="contact_form.php" class="btn btn-primary">Crear Contacto</a>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Ver Lista de Contactos</h5>
                    <p class="card-text">Consulta y gestiona todos tus contactos almacenados.</p>
                    <a href="contact_list.php" class="btn btn-primary">Ver Contactos</a>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Importar Exportar Contactos</h5>
                    <p class="card-text">Importar o exportar contactos</p>
                    <a href="import_export.php" class="btn btn-primary">Ir</a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'partials/footer.php'; ?>
