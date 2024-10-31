<?php
interface IDbAccess {
    public static function getAll();
    public static function select($id);
    public static function insert(Contact $contact); 
    public static function delete($id);
    public static function update(Contact $contact); 
}

?>
