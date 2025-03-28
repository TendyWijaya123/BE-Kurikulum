<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;
use App\Models\Jurusan;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        $jurusanTeknikKimia = Jurusan::where('nama', 'Teknik Kimia')->first();
        if ($jurusanTeknikKimia) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Kimia',
                'jenjang' => 'D3',
                'kode' => 'TK',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D3 Analis Kimia',
                'jenjang' => 'D3',
                'kode' => 'KA',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Kimia Produksi Bersih',
                'jenjang' => 'D4',
                'kode' => 'TK-D4-TKPB',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
        }

        // Teknik Mesin
        $jurusanTeknikMesin = Jurusan::where('nama', 'Teknik Mesin')->first();
        if ($jurusanTeknikMesin) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Mesin',
                'jenjang' => 'D3',
                'kode' => 'ME',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Aeronautika',
                'jenjang' => 'D3',
                'kode' => 'AE',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Perancangan dan Konstruksi Mesin',
                'jenjang' => 'D4',
                'kode' => 'KM',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Proses Manufaktur',
                'jenjang' => 'D4',
                'kode' => 'PM',
                'jurusan_id' => $jurusanTeknikMesin->id,
            ]);
        }

        $jurusanTeknikRefrigerasi = Jurusan::where('nama', 'Teknik Refrigerasi dan Tata Udara')->first();
        if ($jurusanTeknikRefrigerasi) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Pendingin dan Tata Udara',
                'jenjang' => 'D3',
                'kode' => 'TRTU-D3-PTU',
                'jurusan_id' => $jurusanTeknikRefrigerasi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Pendingin dan Tata Udara',
                'jenjang' => 'D4',
                'kode' => 'TRTU-D4-PTU',
                'jurusan_id' => $jurusanTeknikRefrigerasi->id,
            ]);
        }

        // Teknik Konversi Energi
        $jurusanTeknikKonversiEnergi = Jurusan::where('nama', 'Teknik Konversi Energi')->first();
        if ($jurusanTeknikKonversiEnergi) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Konversi Energi',
                'jenjang' => 'D3',
                'kode' => 'EN',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknologi Pembangkit Tenaga Listrik',
                'jenjang' => 'D4',
                'kode' => 'TKE-D4-TPKL',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Konservasi Energi',
                'jenjang' => 'D4',
                'kode' => 'TKE-D4-TKE',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
        }

        $jurusanTeknikElektro = Jurusan::where('nama', 'Teknik Elektro')->first();
        if ($jurusanTeknikElektro) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Elektronika',
                'jenjang' => 'D3',
                'kode' => 'EC',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Listrik',
                'jenjang' => 'D3',
                'kode' => 'LS',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Telekomunikasi',
                'jenjang' => 'D3',
                'kode' => 'TC',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Elektronika',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TE',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Otomasi Industri',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TOI',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Telekomunikasi',
                'jenjang' => 'D4',
                'kode' => 'TE-D4-TKT',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
        }

        $jurusanTeknikSipil = Jurusan::where('nama', 'Teknik Sipil')->first();
        if ($jurusanTeknikSipil) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Konstruksi Sipil',
                'jenjang' => 'D3',
                'kode' => 'KS',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Konstruksi Gedung',
                'jenjang' => 'D3',
                'kode' => 'KG',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Perancangan Jalan dan Jembatan',
                'jenjang' => 'D4',
                'kode' => 'JJ',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Perawatan dan Perbaikan Gedung',
                'jenjang' => 'D4',
                'kode' => 'PG',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'S2 Rekayasa Infrastruktur',
                'jenjang' => 'S2',
                'kode' => 'TS-S2-RI',
                'jurusan_id' => $jurusanTeknikSipil->id,
            ]);
        }

        // Teknik Komputer dan Informatika
        $jurusanTeknikKomputerInformatika = Jurusan::where('nama', 'Teknik Komputer dan Informatika')->first();
        if ($jurusanTeknikKomputerInformatika) {
            Prodi::firstOrCreate([
                'name' => 'D3 Teknik Informatika',
                'jenjang' => 'D3',
                'kode' => 'IF',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Informatika',
                'jenjang' => 'D4',
                'kode' => 'TKI-D4',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
        }
    }
}
