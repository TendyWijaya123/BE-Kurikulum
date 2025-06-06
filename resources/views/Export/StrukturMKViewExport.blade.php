<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>12A_Struktur MK</title>
    </head>

    <body>
        @php
            $colLength = 10;
            $totalRatio = 7;
            $leftColspan = floor((4 / $totalRatio) * $colLength);
            $middleColspan = floor((2 / $totalRatio) * $colLength);
            $rightColspan = $colLength - ($leftColspan + $middleColspan);
        @endphp

        <tr>
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 12 A</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Struktur Mata Kuliah' }}
                </td>
                <td colspan="{{ $middleColspan }}" align="center" style="border: 1px  solid black;">KODE PRODI</td>
                <td colspan="{{ $rightColspan }}" rowspan="3" align="center" style="border: 1px solid black;"></td>
            </tr>
            <tr>
                <td colspan="{{ $middleColspan }}" rowspan="2" align="center"
                    style="color: red; border:1px solid black; font-weight:bold;">
                    [ Kode Prodi dari PDPT ]
                </td>
            </tr>
            <tr>
                <td colspan="{{ $leftColspan }}" align="center"
                    style="color: red; border:1px solid black; font-weight:bold">
                    [ Nomor SK dari WD1 ]
                </td>
            </tr>

            <tr>
                <td colspan="{{ floor(($colLength * 3) / 7) }}"
                    style="border-left: 1px solid black; border-bottom:1px solid black;">JURUSAN:</td>
                <td colspan="{{ floor(($colLength * 2) / 7) }}" style="border-bottom: 1px solid black;">PROGRAM:</td>
                <td colspan="{{ $colLength - (floor(($colLength * 3) / 7) + floor(($colLength * 2) / 7)) }}"
                    style="border-right: 1px solid black; border-bottom:1px solid black;">
                    PROGRAM STUDI
                </td>
            </tr>
            <tr></tr>
            <tr></tr>

            <tr>
                <td></td>
                <td>
                    {{-- KONTEN START --}}



                    @foreach ($mataKuliah as $mkIndex => $mk)
                        <table style="border-collapse: collapse; width: 100%;" border="1">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">No
                                    </th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">Nama
                                        Mata Kuliah</th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        Tujuan Belajar</th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        Kemampuan Akhir yang Direncanakan</th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        Estimasi Beban Waktu Belajar</th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        Bentuk Pembelajaran</th>
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        Metode Pembelajaran</th>
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
                                                <td style="border: 1px solid black;"
                                                    rowspan="{{ $totalKemampuan + 2 }}">{{ $mkIndex + 1 }}</td>
                                                <td style="border: 1px solid black;"
                                                    rowspan="{{ $totalKemampuan + 2 }}">{{ $mk->nama }}</td>
                                                <td style="border: 1px solid black;"
                                                    rowspan="{{ $totalKemampuan + 2 }}">{{ $mk->tujuan }}</td>
                                            @endif
                                            <td style="border: 1px solid black;">{{ $ka->deskripsi }}</td>
                                            <td style="border: 1px solid black;">{{ $ka->estimasi_beban_belajar }}</td>
                                            <td style="border: 1px solid black;">
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

                                    <tr>
                                        <td style="border: 1px solid black;"><strong>Total Kebutuhan Waktu
                                                Belajar</strong></td>
                                        <td style="border: 1px solid black;"><strong>{{ $totalBebanBelajar }}</strong>
                                        </td>
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
                                        <td style="border: 1px solid black;">{{ $mkIndex + 1 }}</td>
                                        <td style="border: 1px solid black;">{{ $mk->nama }}</td>
                                        <td style="border: 1px solid black;">{{ $mk->tujuan }}</td>
                                        <td colspan="4" style="text-align: center; border: 1px solid black;">Tidak
                                            ada data kemampuan akhir</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <table>
                            <tr></tr>
                        </table>
                    @endforeach



                    {{-- KONTEN END --}}
                </td>
            </tr>



            {{-- FOOTER START --}}
            <tr>
                <td colspan="{{ floor(($colLength * 3) / 7) }}" style="background-color: orange;">No Rev:001</td>
                <td colspan="{{ floor(($colLength * 2) / 7) }}" style="background-color: orange;">Tgl. Berlaku: Agustus
                    2025</td>
                <td colspan="{{ $colLength - (floor(($colLength * 3) / 7) + floor(($colLength * 2) / 7)) }}"
                    style="background-color: orange;">
                    Hal... dari ...
                </td>
            </tr>
            <tr></tr>

            <tr>
                <td align="center" colspan={{ $colLength }}
                    style="font-weight: bold; font-size:13px; border-top:1px solid black;">DOKUMEN
                    INTERNAL POLBAN</td>
            </tr>

            {{-- FOOTER END --}}
        </table>

    </body>

</html>
