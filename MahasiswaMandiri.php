<?php
// MahasiswaMandiri.php
require_once 'Mahasiswa.php';
require_once 'Database.php';

class MahasiswaMandiri extends Mahasiswa {
    // Properti tambahan spesifik
    private ?string $golonganUKT;
    private ?string $namaWali;

    // Constructor Kelas Anak
    public function __construct(
        int $id_mahasiswa = 0, 
        string $nama_mahasiswa = "", 
        string $nim = "", 
        int $semester = 0, 
        float $tarif_ukt_nominal = 0.0,
        ?string $golonganUKT = null,
        ?string $namaWali = null
    ) {
        // Meneruskan atribut global ke constructor milik class induk (Mahasiswa)
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->golonganUKT = $golonganUKT;
        $this->namaWali = $namaWali;
    }

    // Implementasi metode abstrak wajib: Mandiri membayar UKT penuh
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal;
    }

    // Implementasi metode abstrak wajib: Menampilkan spesifikasi akademis Mandiri
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Mandiri]</h3>";
        echo "ID Mahasiswa : " . $this->id_mahasiswa . "<br>";
        echo "Nama         : " . $this->nama_mahasiswa . "<br>";
        echo "NIM          : " . $this->nim . "<br>";
        echo "Semester     : " . $this->semester . "<br>";
        echo "Golongan UKT : " . ($this->golonganUKT ?? '-') . "<br>";
        echo "Nama Wali    : " . ($this->namaWali ?? '-') . "<br>";
        echo "Total Tagihan: Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . "<br>";
        echo "<hr>";
    }

    // METHOD SPESIFIK: Query SELECT-WHERE data mandiri berdasarkan NIM
    public function ambilDataDariDatabase(string $nim): bool {
        $db = new Database();
        $koneksi = $db->getKoneksi();
        
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'mandiri' AND nim = ?";
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
            $this->golonganUKT = $row['golongan_ukt'];
            $this->namaWali = $row['nama_wali'];
            return true;
        }
        return false;
    }
}