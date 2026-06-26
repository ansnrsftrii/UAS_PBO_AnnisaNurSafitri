<?php
// Hubungkan dengan file abstract class induk (Tahap 3) dan Database
require_once 'Mahasiswa.php';
require_once 'Database.php';

// =========================================================================
// 1. SUBCLASS: MahasiswaMandiri
// =========================================================================
class MahasiswaMandiri extends Mahasiswa {
    // [Tahap 4] Properti tambahan spesifik
    private ?string $golonganUKT;
    private ?string $namaWali;

    // [Tahap 4] Constructor Kelas Anak
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

    // [Tahap 5] POLYMORPHISM OVERRIDING: Logika Mandiri (+100.000 biaya operasional)
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal + 100000.00;
    }

    // [Tahap 4] Implementasi metode abstrak menampilkan data akademis
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Mandiri]</h3>";
        echo "ID Mahasiswa : " . $this->id_mahasiswa . "<br>";
        echo "Nama         : " . $this->nama_mahasiswa . "<br>";
        echo "NIM          : " . $this->nim . "<br>";
        echo "Semester     : " . $this->semester . "<br>";
        echo "Golongan UKT : " . ($this->golonganUKT ?? '-') . "<br>";
        echo "Nama Wali    : " . ($this->namaWali ?? '-') . "<br>";
        echo "Tarif Pokok  : Rp " . number_format($this->tarif_ukt_nominal, 2, ',', '.') . "<br>";
        echo "Biaya Praktikum : Rp 100.000,00 (Flat)<br>";
        echo "<b>Total Tagihan Semester: Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . "</b><br>";
        echo "<hr>";
    }

    // [Tahap 4] METHOD SPESIFIK: Query SELECT-WHERE data mandiri berdasarkan NIM
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


// =========================================================================
// 2. SUBCLASS: MahasiswaBidikmisi
// =========================================================================
class MahasiswaBidikmisi extends Mahasiswa {
    // [Tahap 4] Properti tambahan spesifik
    private ?string $nomorKipKuliah;
    private ?float $danaSakuSubsidi;

    // [Tahap 4] Constructor Kelas Anak
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

    // [Tahap 5] POLYMORPHISM OVERRIDING: Logika Bidikmisi (Gratis penuh / 0)
    public function hitungTagihanSemester(): float {
        return 0.00; 
    }

    // [Tahap 4] Implementasi metode abstrak menampilkan data akademis
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Bidikmisi / KIP-K]</h3>";
        echo "ID Mahasiswa      : " . $this->id_mahasiswa . "<br>";
        echo "Nama              : " . $this->nama_mahasiswa . "<br>";
        echo "NIM               : " . $this->nim . "<br>";
        echo "Semester          : " . $this->semester . "<br>";
        echo "No. KIP Kuliah    : " . ($this->nomorKipKuliah ?? '-') . "<br>";
        echo "Dana Saku Subsidi : Rp " . number_format($this->danaSakuSubsidi ?? 0, 2, ',', '.') . "/bulan<br>";
        echo "<b>Total Tagihan Semester: Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (GRATIS - Ditanggung Negara)</b><br>";
        echo "<hr>";
    }

    // [Tahap 4] METHOD SPESIFIK: Query SELECT-WHERE data bidikmisi berdasarkan NIM
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


// =========================================================================
// 3. SUBCLASS: MahasiswaPrestasi
// =========================================================================
class MahasiswaPrestasi extends Mahasiswa {
    // [Tahap 4] Properti tambahan spesifik
    private ?string $namaInstansiBeasiswa;
    private ?float $minimalIpkSyarat;

    // [Tahap 4] Constructor Kelas Anak
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

    // [Tahap 5] POLYMORPHISM OVERRIDING: Logika Prestasi (Potongan 75%, bayar 25%)
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal * 0.25;
    }

    // [Tahap 4] Implementasi metode abstrak menampilkan data akademis
    public function tampilkanSpesifikasiAkademik(): void {
        echo "<h3>[Skema Pembiayaan: Jalur Prestasi]</h3>";
        echo "ID Mahasiswa       : " . $this->id_mahasiswa . "<br>";
        echo "Nama               : " . $this->nama_mahasiswa . "<br>";
        echo "NIM                : " . $this->nim . "<br>";
        echo "Semester           : " . $this->semester . "<br>";
        echo "Instansi Beasiswa  : " . ($this->namaInstansiBeasiswa ?? '-') . "<br>";
        echo "Syarat Minimal IPK : " . number_format($this->minimalIpkSyarat ?? 0.0, 2) . "<br>";
        echo "Tarif Pokok Asli   : Rp " . number_format($this->tarif_ukt_nominal, 2, ',', '.') . "<br>";
        echo "<b>Total Tagihan Semester: Rp " . number_format($this->hitungTagihanSemester(), 2, ',', '.') . " (Mendapat Potongan Beasiswa 75%)</b><br>";
        echo "<hr>";
    }

    // [Tahap 4] METHOD SPESIFIK: Query SELECT-WHERE data prestasi berdasarkan NIM
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