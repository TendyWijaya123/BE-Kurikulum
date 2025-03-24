<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>12A_Struktur_MK</title>
    </head>

    <body>
        @foreach ($mataKuliah as $mkIndex => $mk)
            <table border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Tujuan Belajar</th>
                        <th>Kemampuan Akhir yang Direncanakan</th>
                        <th>Estimasi Beban Waktu Belajar</th>
                        <th>Bentuk Pembelajaran</th>
                        <th>Metode Pembelajaran</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalKemampuan = count($mk->kemampuanAkhirs);
                        $totalBebanBelajar = $mk->kemampuanAkhirs->sum('estimasi_beban_belajar');
                    @endphp

                    @if ($totalKemampuan > 0)
                        @foreach ($mk->kemampuanAkhirs as $index => $ka)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $totalKemampuan + 2 }}">{{ $mkIndex + 1 }}</td>
                                    <td rowspan="{{ $totalKemampuan + 2 }}">{{ $mk->nama }}</td>
                                    <td rowspan="{{ $totalKemampuan + 2 }}">{{ $mk->tujuan }}</td>
                                @endif

                                <td>{{ $ka->deskripsi }}</td>
                                <td>{{ $ka->estimasi_beban_belajar }}</td>
                                <td>
                                    @foreach ($ka->bentukPembelajarans as $bp)
                                        {{ $bp->nama }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($ka->metodePembelajarans as $mp)
                                        {{ $mp->nama }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach

                        {{-- Baris Total Kebutuhan Waktu Belajar --}}
                        <tr>
                            <td><strong>Total Kebutuhan Waktu Belajar</strong></td>
                            <td><strong>{{ $totalBebanBelajar }}</strong></td>
                            <td>Proses Pembelajaran</td>
                            <td>Strategi Pembelajaran</td>
                        </tr>

                        <tr>
                            <td><strong>Beban Belajar (SKS)</strong></td>
                            <td><strong>{{ $mk->sks }}</strong></td>
                            <td>{{ $mk->kategori }}</td>
                            <td>Elaborasi</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $mkIndex + 1 }}</td>
                            <td>{{ $mk->nama }}</td>
                            <td>{{ $mk->tujuan }}</td>
                            <td colspan="4" style="text-align: center;">Tidak ada data kemampuan akhir</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
    </body>

</html>
