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
                'kode' => 'PB',
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
                'kode' => 'RF',
                'jurusan_id' => $jurusanTeknikRefrigerasi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Pendingin dan Tata Udara',
                'jenjang' => 'D4',
                'kode' => 'RT',
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
                'kode' => 'PL',
                'jurusan_id' => $jurusanTeknikKonversiEnergi->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Konservasi Energi',
                'jenjang' => 'D4',
                'kode' => 'KE',
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
                'kode' => 'EK',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Otomasi Industri',
                'jenjang' => 'D4',
                'kode' => 'OI',
                'jurusan_id' => $jurusanTeknikElektro->id,
            ]);
            Prodi::firstOrCreate([
                'name' => 'D4 Teknik Telekomunikasi',
                'jenjang' => 'D4',
                'kode' => 'NK',
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
                'kode' => 'TI',
                'jurusan_id' => $jurusanTeknikKomputerInformatika->id,
            ]);
        }


        $jurusanAkuntansi = Jurusan::where('nama', 'Akuntansi')->first();

        if ($jurusanAkuntansi) {
            Prodi::firstOrCreate([
                'name' => 'D3 Akuntansi',
                'jenjang' => 'D3',
                'kode' => 'AK',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D3 Keuangan Perbankan',
                'jenjang' => 'D3',
                'kode' => 'KP',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Akuntansi',
                'jenjang' => 'D4',
                'kode' => 'AC',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Akuntansi Manajemen Pemerintahan',
                'jenjang' => 'D4',
                'kode' => 'AP',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Keuangan Syariah',
                'jenjang' => 'D4',
                'kode' => 'SY',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'S-2 Keuangan & Perbankan Syariah',
                'jenjang' => 'S2',
                'kode' => 'KPS2',
                'jurusan_id' => $jurusanAkuntansi->id,
            ]);
        }

        $jurusanAdministrasiNiaga = Jurusan::where('nama', 'Administrasi Niaga')->first();
        if ($jurusanAdministrasiNiaga) {
            Prodi::firstOrCreate([
                'name' => 'D3 Administrasi Bisnis',
                'jenjang' => 'D3',
                'kode' => 'AB',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D3 Manajemen Pemasaran',
                'jenjang' => 'D3',
                'kode' => 'MP',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D3 Usaha Perjalanan Wisata',
                'jenjang' => 'D3',
                'kode' => 'UP',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Administrasi Bisnis',
                'jenjang' => 'D4',
                'kode' => 'BA',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Manajemen Aset',
                'jenjang' => 'D4',
                'kode' => 'MA',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Manajemen Pemasaran',
                'jenjang' => 'D4',
                'kode' => 'MM',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D4 Destinasi Pariwisata',
                'jenjang' => 'D4',
                'kode' => 'DP',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'S2 Pemasaran, Inovasi, dan Teknologi',
                'jenjang' => 'S2',
                'kode' => 'PIT',
                'jurusan_id' => $jurusanAdministrasiNiaga->id,
            ]);
        }



        $jurusanBahasaInggris = Jurusan::where('nama', 'Bahasa Inggris')->first();
        if ($jurusanBahasaInggris) {
            Prodi::firstOrCreate([
                'name' => 'D3 Bahasa Inggris',
                'jenjang' => 'D3',
                'kode' => 'IG',
                'jurusan_id' => $jurusanBahasaInggris->id,
            ]);
        }
    }
}
