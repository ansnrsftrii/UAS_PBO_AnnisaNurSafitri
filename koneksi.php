<?php
// Database.php

class Database {
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "db_uas_pbo_trpl1a_annisanursafitri";
    protected ?mysqli $koneksi = null;

    public function __construct() {
        $this->koneksi = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->koneksi->connect_error) {
            die("Koneksi ke database gagal: " . $this->koneksi->connect_error);
        }
    }

    public function getKoneksi(): mysqli {
        return $this->koneksi;
    }
}