<?php
// MahasiswaPrestasi.php
require_once 'Mahasiswa.php';
require_once 'Database.php';

class MahasiswaPrestasi extends Mahasiswa {
    // Properti tambahan spesifik
    private ?string $namaInstansiBeasiswa;
    private ?float $minimalIpkSyarat;

    // Constructor Kelas Anak
    public function __construct(
        int $id_mahasiswa = 0, 
        string $nama_mahasiswa = "", 
        string $nim = "", 
        int $semester = 0, 
        float $tarif_ukt_nominal = 0.0,
        ?string $namaInstansiBeasiswa = null,
        ?float $minimalIpkSyarat = null
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        $this->minimalIpkSyarat = $minimalIpkSyarat;
    }

    // Implementasi metode abstrak wajib: Jalur prestasi mendapat potongan 50%
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal * 0.50;
    }

    // Implementasi metode abstrak wajib: Menampilkan spesifikasi akademis Prestasi
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Jalur Prestasi]</h3>";
        echo "ID Mahasiswa       : " . $this->id_mahasiswa . "<br>";
        echo "Nama               : " . $this->nama_mahasiswa . "<br>";
        echo "NIM                : " . $this->nim . "<br>";
        echo "Semester           : " . $this->semester . "<br>";
        echo "Instansi Beasiswa  : " . ($this->namaInstansiBeasiswa ?? '-') . "<br>";
        echo "Syarat Minimal IPK : " . number_format($this->minimalIpkSyarat ?? 0.0, 2) . "<br>";
        echo "Total Tagihan      : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (Diskon Prestasi 50%)<br>";
        echo "<hr>";
    }

    // METHOD SPESIFIK: Query SELECT-WHERE data prestasi berdasarkan NIM
    public function ambilDataDariDatabase(string $nim): bool {
        $db = new Database();
        $koneksi = $db->getKoneksi();
        
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'prestasi' AND nim = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->id_mahasiswa = (int)$row['id_mahasiswa'];
            $this->nama_mahasiswa = $row['nama_mahasiswa'];
            $this->nim = $row['nim'];
            $this->semester = (int)$row['semester'];
            $this->tarif_ukt_nominal = (float)$row['tarif_ukt_nominal'];
            $this->namaInstansiBeasiswa = $row['nama_instansi_beasiswa'];
            $this->minimalIpkSyarat = (float)$row['minimal_ipk_syarat'];
            return true;
        }
        return false;
    }
}