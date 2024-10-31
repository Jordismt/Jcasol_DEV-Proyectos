<?php
require_once 'IDbAccess.php';
require_once 'DBConnection.php';
require_once 'Contact.php';

class ContactRepository implements IDbAccess {
    public static function getAll() {
        $conn = DBConnection::connect();
        $query = $conn->query("SELECT * FROM contacts");
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir cada fila de datos en un objeto Contact
        return array_map(function($item) {
            return new Contact($item);
        }, $data);
    }
    
    public static function select($id) {
        $conn = DBConnection::connect();
        $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar si se encontró un contacto y convertir a objeto
        if ($data) {
            return new Contact($data);
        }
        return null; 
    }
    
    public static function insert(Contact $contact) {
        $pdo = DBConnection::connect();  // Conexión a la base de datos
        
        $sql = "INSERT INTO contacts (title, name, surname, birthDate, phone, email, favourite, important, archived) 
                VALUES (:title, :name, :surname, :birthDate, :phone, :email, :favourite, :important, :archived)";
        
        $stmt = $pdo->prepare($sql);
        
        // Vincula los valores usando los métodos getter
        $stmt->bindValue(':title', $contact->getTitle());
        $stmt->bindValue(':name', $contact->getName());
        $stmt->bindValue(':surname', $contact->getSurname());

        // Asegúrate de que birthDate tenga un valor válido o sea NULL
        $birthDate = $contact->getBirthdate();
        $stmt->bindValue(':birthDate', empty($birthDate) ? null : $birthDate);
        
        $stmt->bindValue(':phone', $contact->getPhone());
        $stmt->bindValue(':email', $contact->getEmail());
        $stmt->bindValue(':favourite', $contact->isFavourite(), PDO::PARAM_BOOL);
        $stmt->bindValue(':important', $contact->isImportant(), PDO::PARAM_BOOL);
        $stmt->bindValue(':archived', $contact->isArchived(), PDO::PARAM_BOOL);
        
        return $stmt->execute(); 
    }

    public static function delete($id) {
        $conn = DBConnection::connect();
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function update(Contact $contact) {
        $conn = DBConnection::connect();
        $stmt = $conn->prepare("UPDATE contacts 
            SET title = :title, name = :name, surname = :surname, birthDate = :birthDate, 
                phone = :phone, email = :email, favourite = :favourite, 
                important = :important, archived = :archived 
            WHERE id = :id");
        
        try {
            return $stmt->execute([
                'id' => $contact->getId(), 
                'title' => $contact->getTitle(),
                'name' => $contact->getName(),
                'surname' => $contact->getSurname(),
                'birthDate' => empty($contact->getBirthdate()) ? null : $contact->getBirthdate(),
                'phone' => $contact->getPhone(),
                'email' => $contact->getEmail(),
                'favourite' => $contact->isFavourite(),
                'important' => $contact->isImportant(),
                'archived' => $contact->isArchived()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage(); // Muestra el error
            return false;
        }
    }

    public static function exportContactsToJSON() {
        $contacts = self::getAll(); // Asegúrate de que este método no esté causando problemas
    
        // Verifica que obtienes contactos
        if (empty($contacts)) {
            echo "<div class='alert alert-warning'>No hay contactos para exportar.</div>";
            return;
        }
    
        $data = ['Contacts' => array_map(function($contact) {
            return [
                'id' => $contact->getId(),
                'title' => $contact->getTitle(),
                'name' => $contact->getName(),
                'surname' => $contact->getSurname(),
                'birthDate' => $contact->getBirthdate(),
                'phone' => $contact->getPhone(),
                'email' => $contact->getEmail(),
                'favourite' => $contact->isFavourite(),
                'important' => $contact->isImportant(),
                'archived' => $contact->isArchived()
            ];
        }, $contacts)];
    
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<div class='alert alert-danger'>Error al codificar los contactos a JSON: " . json_last_error_msg() . "</div>";
            return;
        }
    
        $filePath = 'downloads/contacts.json';
        if (file_put_contents($filePath, $jsonContent) === false) {
            echo "<div class='alert alert-danger'>Error al guardar el archivo JSON.</div>";
            return;
        }
    
        // Establecer los encabezados para forzar la descarga
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="contacts.json"');
        readfile($filePath);
        exit;
    }
    
    public static function importContactsFromJSON($filePath) {
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);
    
        if (isset($data['Contacts'])) {
            foreach ($data['Contacts'] as $contactData) {
                // Verificar que birthDate no esté vacío y tenga formato válido
                if (empty($contactData['birthDate'])) {
                    // Decide si omitir el contacto o manejar el valor predeterminado
                    // continue; // O asignar una fecha predeterminada aquí
                    $contactData['birthDate'] = null; // O alguna fecha por defecto
                }
    
                // Crear el objeto Contact
                $contact = new Contact($contactData);
                // Insertar el contacto
                self::insert($contact);
            }
        }
    }
}
?>
