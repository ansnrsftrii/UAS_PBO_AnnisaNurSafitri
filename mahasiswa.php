<?php
// Mahasiswa.php

// Mengambil atau menghubungkan berkas Database agar siap digunakan oleh kelas ini/turunannya
require_once 'Database.php';

abstract class Mahasiswa {
    
    // Properti/Atribut Terenkapsulasi (Protected)
    // Nilai ini wajib dipetakan pas dari kolom tabel database Tahap 1
    protected int $id_mahasiswa;
    protected string $nama_mahasiswa;
    protected string $nim;
    protected int $semester;
    protected float $tarif_ukt_nominal; // float memetakan tipe DECIMAL dari MySQL

    // Constructor untuk memetakan langsung data dari kolom database ke properti objek
    public function __construct(
        int $id_mahasiswa, 
        string $nama_mahasiswa, 
        string $nim, 
        int $semester, 
        float $tarif_ukt_nominal
    ) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarif_ukt_nominal = $tarif_ukt_nominal;
    }

    // =======================================================
    // DEKLARASI METODE ABSTRAK (Wajib tanpa isi/body)
    // =======================================================

    /**
     * Metode abstrak untuk menghitung tagihan semester.
     * @return float
     */
    abstract public function hitungTagihanSemester(): float;

    /**
     * Metode abstrak untuk menampilkan spesifikasi akademik mahasiswa.
     * @return void
     */
    abstract public function tampilkanSpesifikasiAkademik(): void;


    // =======================================================
    // GETTER (Opsional: Untuk mengakses properti protected dari luar kelas jika diperlukan)
    // =======================================================
    
    public function getIdMahasiswa(): int { return $this->id_mahasiswa; }
    public function getNamaMahasiswa(): string { return $this->nama_mahasiswa; }
    public function getNim(): string { return $this->nim; }
    public function getSemester(): int { return $this->semester; }
    public function getTarifUktNominal(): float { return $this->tarif_ukt_nominal; }
}