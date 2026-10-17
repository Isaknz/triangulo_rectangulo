<?php
class Database{
    private string $host= 'localhost';
    private string $db = 'triangulo_db';
    private string $user = 'root';
    private string $pass = '12021706';
    private string $charset = 'utf8mb4';

    public function conectar(): PDO{
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            return new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false 
            ]);
        } catch (PDOException $e) {
            die('Error de conexion a la base de datos: '.$e->getMessage());
        }
    }
}