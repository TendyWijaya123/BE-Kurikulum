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
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">No</th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Nama Mata Kuliah
                        </th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Tujuan Belajar</th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Kemampuan Akhir
                            yang Direncanakan
                        </th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Estimasi Beban
                            Waktu Belajar</th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Bentuk Pembelajaran
                        </th>
                        <th style="border: 1px solid  black; background-color:gray; font-weight:bold">Metode Pembelajaran
                        </th>
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
                                    <td style="border: 1px solid black;" rowspan="{{ $totalKemampuan + 2 }}">
                                        {{ $mkIndex + 1 }}</td>
                                    <td style="border: 1px solid black;" rowspan="{{ $totalKemampuan + 2 }}">
                                        {{ $mk->nama }}</td>
                                    <td style="border: 1px solid black;" rowspan="{{ $totalKemampuan + 2 }}">
                                        {{ $mk->tujuan }}</td>
                                @endif

                                <td style="border: 1px solid  black">{{ $ka->deskripsi }}</td>
                                <td style="border: 1px solid  black">{{ $ka->estimasi_beban_belajar }}</td>
                                <td style="border: 1px solid  black">
                                    @foreach ($ka->bentukPembelajarans as $bp)
                                        {{ $bp->nama }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                                <td style="border: 1px solid black;">
                                    @foreach ($ka->metodePembelajarans as $mp)
                                        {{ $mp->nama }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach

                        {{-- Baris Total Kebutuhan Waktu Belajar --}}
                        <tr>
                            <td style="border: 1px solid black;"><strong>Total Kebutuhan Waktu Belajar</strong></td>
                            <td style="border: 1px solid black;"><strong>{{ $totalBebanBelajar }}</strong></td>
                            <td style="border: 1px solid black;">Proses Pembelajaran</td>
                            <td style="border: 1px solid black;">Strategi Pembelajaran</td>
                        </tr>

                        <tr>
                            <td style="border: 1px solid black;"><strong>Beban Belajar (SKS)</strong></td>
                            <td style="border: 1px solid black;"><strong>{{ $mk->sks }}</strong></td>
                            <td style="border: 1px solid black;">{{ $mk->kategori }}</td>
                            <td style="border: 1px solid black;">Elaborasi</td>
                        </tr>
                    @else
                        <tr>
                            <td style="border: 1px solid black">{{ $mkIndex + 1 }}</td>
                            <td style="border: 1px solid black">{{ $mk->nama }}</td>
                            <td style="border: 1px solid black">{{ $mk->tujuan }}</td>
                            <td colspan="4" style="text-align: center; border:1px solid black;">Tidak ada data
                                kemampuan akhir</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
    </body>

</html>
