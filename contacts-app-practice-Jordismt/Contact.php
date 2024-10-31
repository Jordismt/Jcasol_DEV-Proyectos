<?php

class Contact {
    private int $id;
    private string $title;
    private string $name;
    private string $surname;
    private string $birthdate;
    private string $phone;
    private string $email;
    private bool $favourite;
    private bool $important;
    private bool $archived;

    public function __construct(array $contactArray) {
        $this->id = (int)($contactArray['id'] ?? 0);  
        $this->title = (string)($contactArray['title'] ?? '');
        $this->name = (string)($contactArray['name'] ?? '');
        $this->surname = (string)($contactArray['surname'] ?? '');
        $this->birthdate = (string)($contactArray['birthdate'] ?? '2000-01-01');  
        $this->phone = (string)($contactArray['phone'] ?? '');
        $this->email = (string)($contactArray['email'] ?? '');
        $this->favourite = !empty($contactArray['favourite']); 
        $this->important = !empty($contactArray['important']);
        $this->archived = !empty($contactArray['archived']); 
    }
    
    public function __toString(): string {
        return "{$this->title} {$this->name} {$this->surname} - {$this->email}";
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getName(): string { return $this->name; }
    public function getSurname(): string { return $this->surname; }
    public function getBirthdate(): string { return $this->birthdate; }
    public function getPhone(): string { return $this->phone; }
    public function getEmail(): string { return $this->email; }
    public function isFavourite(): bool { return $this->favourite; }
    public function isImportant(): bool { return $this->important; }
    public function isArchived(): bool { return $this->archived; }

    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setName(string $name): void { $this->name = $name; }
    public function setSurname(string $surname): void { $this->surname = $surname; }
    public function setBirthdate(string $birthdate): void { $this->birthdate = $birthdate; }
    public function setPhone(string $phone): void { $this->phone = $phone; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setFavourite(bool $favourite): void { $this->favourite = $favourite; }
    public function setImportant(bool $important): void { $this->important = $important; }
    public function setArchived(bool $archived): void { $this->archived = $archived; }

    // Método para validar la fecha de nacimiento
    private function checkContactDate(): bool {
        $date = $this->birthdate;
        
        if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false; // Fecha vacía o en un formato no válido
        }
    
        [$year, $month, $day] = explode('-', $date);
    
        $year = (int) $year;
        $month = (int) $month;
        $day = (int) $day;
    
        if ($year < 1900 || $year > 2100) {
            return false;
        }
    
        $daysInMonth = [1 => 31, 2 => $this->isLeapYear($year) ? 29 : 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31];
    
        return ($month >= 1 && $month <= 12 && $day >= 1 && $day <= $daysInMonth[$month]);
    }
    

    // Método para calcular los días hasta el siguiente cumpleaños
    public function checkBirthday(): ?int {
        if (!$this->checkContactDate()) {
            return null; // Fecha no válida
        }

        $birthday = new DateTime($this->birthdate);
        $today = new DateTime('today');
        $currentYearBirthday = new DateTime($today->format('Y') . '-' . $birthday->format('m-d'));

        if ($currentYearBirthday < $today) {
            $currentYearBirthday->modify('+1 year');
        }

        $interval = $today->diff($currentYearBirthday);

        return (int) $interval->days;
    }

    // Método para determinar si un año es bisiesto
    private function isLeapYear(int $year): bool {
        return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
    }
    
    public function validate() {
        $errors = [];
    
        if (empty($this->name)) {
            $errors[] = "El nombre es obligatorio.";
        }
    
        if (empty($this->surname)) {
            $errors[] = "El apellido es obligatorio.";
        }
    
        // Validar que la fecha de nacimiento sea válida
        if (!$this->checkContactDate()) {
            $errors[] = "La fecha de nacimiento no es válida.";
        }
    
        if (!preg_match('/^\d{9}$/', $this->phone)) {
            $errors[] = "El teléfono debe contener 9 dígitos.";
        }
    
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico no es válido.";
        }
    
        return $errors;
    }
    
    

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'surname' => $this->surname,
            'birthdate' => $this->birthdate,
            'phone' => $this->phone,
            'email' => $this->email,
            'favourite' => $this->favourite,
            'important' => $this->important,
            'archived' => $this->archived,
        ];
    }
}
?>
