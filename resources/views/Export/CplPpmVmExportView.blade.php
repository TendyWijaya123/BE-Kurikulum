<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>6_CPL-PPM-VM</title>
    </head>

    <body>
        @php
            $colLength = 8;
            $totalRatio = 7;
            $leftColspan = floor((4 / $totalRatio) * $colLength);
            $middleColspan = floor((2 / $totalRatio) * $colLength);
            $rightColspan = $colLength - ($leftColspan + $middleColspan);
        @endphp

        <tr>
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 4-a</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Analisis Konsideran (Siap Kerja & Siap Usaha)' }}
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
                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th style="font-weight: 500;" bgcolor="yellow" colspan="3">Rancangan CPL Prodi
                                    Setelah AK4(penyelarasan dengan
                                    level KKNI)
                                </th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">No</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Daftar CPL</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cpl as $index => $item)
                                <tr>
                                    <td style="border:1px solid black; ">{{ $index + 1 }}</td>
                                    <td style="border:1px solid black; ">{{ $item->kode }}</td>
                                    <td style="border:1px solid black; ">{{ $item->keterangan }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>
                        </tbody>
                    </table>


                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th style="font-weight: 500;" colspan="3" bgcolor="yellow">Peran di Industri (Setelah
                                    Bekerja 3 s/d 5 tahun)
                                </th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">No</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Jabatan</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peranIndustri as $index => $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid black;">{{ $item->jabatan }}</td>
                                    <td style="border: 1px solid black;">{{ $item->deskripsi }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>
                        </tbody>
                    </table>


                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th colspan="3" bgcolor="yellow">Profil Profesional Mandiri (PPM) Program Studi
                                    (Setelah
                                    Penyelarasan dengan Lampiran 5)
                                </th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid black;" bgcolor="gray">No</th>
                                <th style="border: 1px solid black;" bgcolor="gray">Daftar PPM</th>
                                <th style="border: 1px solid black;" bgcolor="gray">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ppm as $index => $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid black;">{{ $item->kode }}</td>
                                    <td style="border: 1px solid black;">{{ $item->deskripsi }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>
                        </tbody>
                    </table>
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
