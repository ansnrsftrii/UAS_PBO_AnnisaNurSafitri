<?php
// MahasiswaBidikmisi.php
require_once 'Mahasiswa.php';
require_once 'Database.php';

class MahasiswaBidikmisi extends Mahasiswa {
    // Properti tambahan spesifik
    private ?string $nomorKipKuliah;
    private ?float $danaSakuSubsidi;

    // Constructor Kelas Anak
    public function __construct(
        int $id_mahasiswa = 0, 
        string $nama_mahasiswa = "", 
        string $nim = "", 
        int $semester = 0, 
        float $tarif_ukt_nominal = 0.0,
        ?string $nomorKipKuliah = null,
        ?float $danaSakuSubsidi = null
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->nomorKipKuliah = $nomorKipKuliah;
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    // Implementasi metode abstrak wajib: Bidikmisi digratiskan (Tagihan = 0)
    public function hitungTagihanSemester(): float {
        return 0.00; 
    }

    // Implementasi metode abstrak wajib: Menampilkan spesifikasi akademis Bidikmisi
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Bidikmisi / KIP-K]</h3>";
        echo "ID Mahasiswa      : " . $this->id_mahasiswa . "<br>";
        echo "Nama              : " . $this->nama_mahasiswa . "<br>";
        echo "NIM               : " . $this->nim . "<br>";
        echo "Semester          : " . $this->semester . "<br>";
        echo "No. KIP Kuliah    : " . ($this->nomorKipKuliah ?? '-') . "<br>";
        echo "Dana Saku Subsidi : Rp " . number_format($this->danaSakuSubsidi ?? 0, 2, ',', '.') . "/bulan<br>";
        echo "Total Tagihan     : Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (GRATIS)<br>";
        echo "<hr>";
    }

    // METHOD SPESIFIK: Query SELECT-WHERE data bidikmisi berdasarkan NIM
    public function ambilDataDariDatabase(string $nim): bool {
        $db = new Database();
        $koneksi = $db->getKoneksi();
        
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'bidikmisi' AND nim = ?";
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
            $this->nomorKipKuliah = $row['nomor_kip_kuliah'];
            $this->danaSakuSubsidi = (float)$row['dana_saku_subsidi'];
            return true;
        }
        return false;
    }
}