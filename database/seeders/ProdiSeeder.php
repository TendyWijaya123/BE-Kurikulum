<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;
use App\Models\Jurusan;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        // Teknik Kimia
        $jurusanTeknikKimia = Jurusan::where('nama', 'Teknik Kimia')->first();
        if ($jurusanTeknikKimia) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Kimia',
                'jenjang' => 'D3',
                'kode' => 'TK-D3-TK',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-3 Analis Kimia',
                'jenjang' => 'D3',
                'kode' => 'TK-D3-AK',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Kimia Produksi Bersih',
                'jenjang' => 'D4',
                'kode' => 'TK-D4-TKPB',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
        }

        // Teknik Mesin
        $jurusanTeknikMesin = Jurusan::where('nama', 'Teknik Mesin')->first();
        if ($jurusanTeknikMesin) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Mesin',
                'jenjang' => 'D3',
                'kode' => 'TM-D3-TM',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Aeronautika',
                'jenjang' => 'D3',
                'kode' => 'TM-D3-TA',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Perancangan dan Konstruksi Mesin',
                'jenjang' => 'D4',
                'kode' => 'TM-D4-TPKM',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Proses Manufaktur',
                'jenjang' => 'D4',
                'kode' => 'TM-D4-PM',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
        }

        // Teknik Refrigerasi dan Tata Udara
        $jurusanTeknikRefrigerasi = Jurusan::where('nama', 'Teknik Refrigerasi dan Tata Udara')->first();
        if ($jurusanTeknikRefrigerasi) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Pendingin dan Tata Udara',
                'jenjang' => 'D3',
                'kode' => 'TRTU-D3-PTU',
                'jurusan_id' => $jurusanTeknikRefrigerasi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Pendingin dan Tata Udara',
                'jenjang' => 'D4',
                'kode' => 'TRTU-D4-PTU',
                'jurusan_id' => $jurusanTeknikRefrigerasi->id,
            ]);
        }

        // Teknik Konversi Energi
        $jurusanTeknikKonversiEnergi = Jurusan::where('nama', 'Teknik Konversi Energi')->first();
        if ($jurusanTeknikKonversiEnergi) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Konversi Energi',
                'jenjang' => 'D3',
                'kode' => 'TKE-D3',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknologi Pembangkit Tenaga Listrik',
                'jenjang' => 'D4',
                'kode' => 'TKE-D4-TPKL',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Konservasi Energi',
                'jenjang' => 'D4',
                'kode' => 'TKE-D4-TKE',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
        }

        // Teknik Elektro
        $jurusanTeknikElektro = Jurusan::where('nama', 'Teknik Elektro')->first();
        if ($jurusanTeknikElektro) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Elektro',
                'jenjang' => 'D3',
                'kode' => 'TE-D3',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Listrik',
                'jenjang' => 'D3',
                'kode' => 'TE-D3-TL',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Telekomunikasi',
                'jenjang' => 'D3',
                'kode' => 'TE-D3-TK',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Elektronika',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TE',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Otomasi Industri',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TOI',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Telekomunikasi',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TKT',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
        }

        // Teknik Sipil
        $jurusanTeknikSipil = Jurusan::where('nama', 'Teknik Sipil')->first();
        if ($jurusanTeknikSipil) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Konstruksi Sipil',
                'jenjang' => 'D3',
                'kode' => 'TS-D3-TKS',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Konstruksi Gedung',
                'jenjang' => 'D3',
                'kode' => 'TS-D3-TKG',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Perancangan Jalan dan Jembatan',
                'jenjang' => 'D4',
                'kode' => 'TS-D4-TPJJ',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Perawatan dan Perbaikan Gedung',
                'jenjang' => 'D4',
                'kode' => 'TS-D4-TPPG',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'S-2 Rekayasa Infrastruktur',
                'jenjang' => 'S2',
                'kode' => 'TS-S2-RI',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
        }

        // Teknik Komputer dan Informatika
        $jurusanTeknikKomputerInformatika = Jurusan::where('nama', 'Teknik Komputer dan Informatika')->first();
        if ($jurusanTeknikKomputerInformatika) {
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Informatika',
                'jenjang' => 'D3',
                'kode' => 'TKI-D3',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Informatika',
                'jenjang' => 'D4',
                'kode' => 'TKI-D4',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'S-2 Rekayasa Komputer',
                'jenjang' => 'S2',
                'kode' => 'TKI-S2-RK',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
        }
    }
}
