<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>RPS</title>
    </head>

    <body>
        <table width="100%">
            <tr>
                <td width="100">
                    <img src="{{ public_path('images/logo-polban.png') }}" alt="Logo" width="100">
                </td>
                <td style="text-align: center; font-size: 14px;">
                    <h3 style="margin: 4px 0;">POLITEKNIK NEGERI BANDUNG</h3>
                    <h3 style="color: red; margin: 4px 0;">{{ $mataKuliah->kurikulum->prodi->jurusan->nama }}</h3>
                    <h3 style="color: red; margin: 4px 0;">{{ $mataKuliah->kurikulum->prodi->name }}</h3>
                    <h3 style="margin: 8px 0;">RENCANA PEMBELAJARAN SEMESTER (RPS)</h3>
                </td>
            </tr>
        </table>

        <br>

        <table width="100%" style="border-collapse: collapse; font-size: 11px;">
            <tr>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Nama Mata Kuliah</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Kode Mata Kuliah</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Satuan Kredit Semester</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Semester</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Tanggal Penyusunan</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;">
                    {{ $mataKuliah->nama }} <i>({{ $mataKuliah->nama_inggris }})</i>
                </td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;">{{ $mataKuliah->kode }}</td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;">
                    {{ $mataKuliah->total_teori + $mataKuliah->total_praktek }} ({{ $mataKuliah->total_teori }}T)
                    ({{ $mataKuliah->total_praktek }}P)
                </td>

                <td style="border: 1px solid black; text-align: center; padding: 12px 0;">{{ $mataKuliah->semester }}
                </td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;"></td>
            </tr>
            <tr>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Otorisasi</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;" colspan="2">Ka KBK</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;" colspan="2">Koord. Prodi
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;"></td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;" colspan="2">
                    {{ $kaKbk ?? '' }}
                </td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;" colspan="2">
                    {{ $koordProdi ?? '' }}
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">Otorisasi</td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;" colspan="2">Ka Jurusan
                </td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;" colspan="2">Wakil
                    Direktur Bidang Akademik</td>
            </tr>

            <tr>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;"></td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;" colspan="2">
                    {{ $kaJurusan ?? '' }}
                </td>
                <td style="border: 1px solid black; text-align: center; padding: 12px 0;" colspan="2">
                    {{ $wakilDirekturAkademik ?? '' }}
                </td>
            </tr>

        </table>


        <br>
        <h1 style="font-size: 14px">Capaian Pembelajaran Lulusan</h1>
        <table width="100%" style="border: 1px solid black; border-collapse: collapse; font-size: 11px;">
            <tr>
                <td colspan="3" style="border: 1px solid black; font-weight: bold; text-align: center;">
                    Capaian Pembelajaran Lulusan yang dibebankan pada mata kuliah
                </td>
                <td style="border: 1px solid black; font-weight: bold; text-align: center;">
                    Level of Learning
                </td>
            </tr>


            @foreach ($mataKuliah->cpls as $cpl)
                <tr>
                    <td style="border: 1px solid black; text-align: center;">{{ $cpl->kode }}</td>
                    <td colspan="2" style="border: 1px solid black;">{{ $cpl->keterangan }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $cpl->pivot->kategori }}</td>
                </tr>
            @endforeach
        </table>


        <br>

        <h1 style="font-size: 14px">Tujuan Belajar</h1>
        <table width="100%" style="border: 1px solid black; border-collapse: collapse; font-size: 11px;">
            <tr>
                <td colspan="5" style="border: 1px solid black; text-align: center; font-weight: bold;">
                    Tujuan Belajar
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; text-align: center; width: 20%;">Kode Tujuan Belajar</td>
                <td colspan="4" style="border: 1px solid black; text-align: center; width: 80%;">Deskripsi</td>
            </tr>

            @if (!empty($mataKuliah->tujuanBelajars) && count($mataKuliah->tujuanBelajars) > 0)
                @foreach ($mataKuliah->tujuanBelajars as $tb)
                    <tr>
                        <td style="border: 1px solid black; text-align: center;">{{ $tb->kode }}</td>
                        <td colspan="4" style="border: 1px solid black;">{{ $tb->deskripsi }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="border: 1px solid black; text-align: center;">Tidak ada tujuan belajar.
                    </td>
                </tr>
            @endif

        </table>


        <br>
        <h1 style="font-size: 14px">Deskripsi Singkat Mata Kuliah</h1>
        <p style="font-size: 12px;">
            {{ $mataKuliah->deskripsi_singkat ?? '-' }}
        </p>
        <p style="font-size: 12px; font-style: italic; margin-top: 8px;">
            {{ $mataKuliah->deskripsi_singkat_inggris ?? '-' }}
        </p>
        <br>

        <h1 style="font-size: 14px">Materi Pembelajaran</h1>
        <p style="font-size: 12px">
            @if (!empty($mataKuliah['materiPembelajarans']) && count($mataKuliah['materiPembelajarans']) > 0)
                {{ collect($mataKuliah['materiPembelajarans'])->pluck('description')->implode('; ') }}
            @else
                -
            @endif
        </p>
        <p style="font-size: 12px">
            <i>{{ $mataKuliah->materi_pembelajaran_inggris }}</i>
        </p>

        <h1 style="font-size: 14px;">Daftar Referensi</h1>
        <ol>
            @foreach ($mataKuliah->bukuReferensis ?? [] as $buku)
                <li style="font-size: 12px">
                    {{ $buku->penulis ?? '-' }},
                    <i>{{ $buku->judul ?? '-' }}</i>,
                    {{ $buku->penerbit ?? '-' }},
                    {{ $buku->tahun_terbit ?? '-' }}
                </li>
            @endforeach
        </ol>
        <br>
        <h1 style="font-size: 14px">Dosen Pengampu</h1>
        <ol>
            @foreach ($mataKuliah->dosens ?? [] as $dosen)
                <li style="font-size: 12px">
                    {{ $dosen->nama ?? '-' }}
                </li>
            @endforeach
        </ol>


        <br>
        <h1 style="font-size: 14px">Rencana Pembelajaran Semester</h1>
        <table width="100%" style="border-collapse: collapse; font-size: 11px;">

            <tbody>
                <tr>
                    <th style="border: 1px solid black;">Minggu ke-</th>
                    <th style="border: 1px solid black;">Kemampuan Akhir yang Direncanakan (KAD)</th>
                    <th style="border: 1px solid black;">Pokok Bahasan</th>
                    <th style="border: 1px solid black;">Modalitas, Bentuk, Strategi, dan Metode Pembelajaran (Media dan
                        Sumber Belajar)</th>
                    <th style="border: 1px solid black;">Beban Belajar Mahasiswa</th>
                    <th style="border: 1px solid black;">Instrumen Penilaian</th>
                    <th style="border: 1px solid black;">Hasil Belajar</th>
                    <th style="border: 1px solid black;">TB</th>
                    <th style="border: 1px solid black;">CPL</th>
                    <th style="border: 1px solid black;">Bobot Penilaian (%)</th>
                </tr>
                <!-- sub-header -->
                <tr>
                    <th style="border: 1px solid black;"><i>Week Number</i></th>
                    <th style="border: 1px solid black;"><i>Planned Final Capability</i></th>
                    <th style="border: 1px solid black;"><i>Subject Matter</i></th>
                    <th style="border: 1px solid black;"><i>Modalities, Forms, Strategies, and Learning Methods</i></th>
                    <th style="border: 1px solid black;"><i>Student Workload</i></th>
                    <th style="border: 1px solid black;"><i>Criteria and Forms of Assessment</i></th>
                    <th style="border: 1px solid black;"><i></i></th>
                    <th style="border: 1px solid black;"><i>Learning Outcomes</i></th>
                    <th style="border: 1px solid black;"><i>Program Learning Outcomes</i></th>
                    <th style="border: 1px solid black;"><i>Assessment Weighted Factor (%)</i></th>
                </tr>
                @foreach ($mataKuliah['rpss'] as $rps)
                    @if (in_array($rps['kategori'], ['EAS', 'ETS']))
                        <tr>
                            <td style="border: 1px solid black;">{{ $rps['minggu'] }}</td>
                            <td colspan="9" style="border: 1px solid black; text-align: center;">
                                {{ $rps['kategori'] }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td style="border: 1px solid black;">{{ $rps['minggu'] }}</td>
                            <td style="border: 1px solid black;">{{ $rps->kemampuanAkhir->deskripsi ?? '-' }}</td>
                            <td style="border: 1px solid black;">{{ $rps['pokok_bahasan'] }}</td>
                            <td style="border: 1px solid black;">
                                {{ $rps['modalitas_bentuk_strategi_metodepembelajaran'] }}
                                @if ($rps['media_pembelajaran'])
                                    <br><strong>Media:</strong> {{ $rps['media_pembelajaran'] }}
                                @endif
                                @if ($rps['sumber_belajar'])
                                    <br><strong>Sumber:</strong> {{ $rps['sumber_belajar'] }}
                                @endif
                            </td>
                            <td style="border: 1px solid black; white">
                                Teori:<br>
                                BT: 1 x 1 x {{ $mataKuliah['teori_bt'] ?? 0 }}'<br>
                                PT: 1 x 1 x {{ $mataKuliah['teori_pt'] ?? 0 }}'<br>
                                M: 1 x 1 x {{ $mataKuliah['teori_m'] ?? 0 }}'<br><br>
                                Praktik:<br>
                                BT: 1 x 1 x {{ $mataKuliah['praktek_bt'] ?? 0 }}'<br>
                                PT: 1 x 1 x {{ $mataKuliah['praktek_pt'] ?? 0 }}'<br>
                                M: 1 x 1 x {{ $mataKuliah['praktek_m'] ?? 0 }}'
                            </td>
                            <td style="border: 1px solid black; padding: 5px;">
                                @if (isset($rps['instrumenPenilaians']) && count($rps['instrumenPenilaians']) > 0)
                                    @foreach ($rps['instrumenPenilaians'] as $penilaian)
                                        <strong>{{ $penilaian['jenis_evaluasi'] }}
                                            ({{ $penilaian['bobot_penilaian'] }}%)
                                        </strong><br>
                                        {{ $penilaian['deskripsi'] ?? '-' }}<br><br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td style="border: 1px solid black;">{{ $rps['hasil_belajar'] }}</td>
                            <td style="border: 1px solid black;">{{ $rps['tujuanBelajar']['kode'] ?? '-' }}</td>
                            <td style="border: 1px solid black;">{{ $rps['cpl']['kode'] ?? '-' }}</td>
                            <td style="border: 1px solid black;">{{ $rps['bobot_penilaian'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <br>

        <h1 style="font-size: 14px">Ringkasan</h1>
        <table width="100%" style="font-size: 11px; border-collapse: collapse;" border="1">
            <tr>
                <td colspan="3" style="font-weight: bold; text-align: center;">Pembobotan Penilaian</td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: center;">Jenis Evaluasi</td>
                <td style="font-weight: bold; text-align: center;">Instrumen</td>
                <td style="font-weight: bold; text-align: center;">Persentase (%)</td>
            </tr>
            <tr>
                <td>Aktivitas Partisipatif</td>
                <td>Case Method/ Problem Based</td>
                <td style="text-align: center;">
                    {{ number_format($ringkasanInstrumen['Case Study'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td>Hasil Proyek</td>
                <td>Project Based</td>
                <td style="text-align: center;">
                    {{ number_format($ringkasanInstrumen['Project'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td rowspan="4" style="vertical-align: middle; text-align: center;">Kognitif</td>
                <td>Tugas</td>
                <td style="text-align: center;">
                    {{-- Kalau tugas punya bobot khusus, bisa ditambahkan di ringkasan, tapi kalau nggak ada, bisa kosong --}}
                    {{-- Misal, kalau 'Tugas' termasuk di 'Reguler' tapi tidak di ringkasan, bisa dikosongkan --}}
                    -
                </td>
            </tr>
            <tr>
                <td>Quiz</td>
                <td style="text-align: center;">
                    {{ number_format($ringkasanInstrumen['Quiz'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td>UTS</td>
                <td style="text-align: center;">
                    {{ number_format($ringkasanInstrumen['ETS'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td>UAS</td>
                <td style="text-align: center;">
                    {{ number_format($ringkasanInstrumen['EAS'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: right;">Total</td>
                <td style="text-align: center; font-weight: bold;">
                    {{ number_format(
                        ($ringkasanInstrumen['Case Study'] ?? 0) +
                            ($ringkasanInstrumen['Project'] ?? 0) +
                            ($ringkasanInstrumen['Quiz'] ?? 0) +
                            ($ringkasanInstrumen['ETS'] ?? 0) +
                            ($ringkasanInstrumen['EAS'] ?? 0),
                        2,
                    ) }}
                </td>
            </tr>
        </table>


        <h1 style="font-size: 14px">Kriteria Penilaian</h1>
        <img width="100%" src="{{ public_path('images/kriteria-penilaian.png') }}" alt="Kriteria Penilaian">


        <h1 style="font-size: 14px">Sanksi</h1>
        <p style="font-size: 12px">Segala kecurangan (cheating, plagiat, copy-paste dan sejenisnya) tidak akan
            ditoleransi. Apabila mahasiswa
            terbukti melakukan perbuatan tersebut, maka akan didiskualifikasi dari kelas dengan nilai maksimal D.
        </p>

        <h1 style="font-size: 14px">Penutup</h1>
        <p style="font-size: 12px">Rencana Pembelajaran Semester (RPS) ini berlaku Tahun Akademik 2025/2026 dan
            seterusnya. RPS ini dievaluasi
            secara berkala setiap semester dan akan dilakukan perbaikan jika dalam penerapannya masih diperlukan
            penyempurnaan.
        </p>



    </body>

</html>
