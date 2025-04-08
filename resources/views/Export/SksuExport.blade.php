<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>4a_AK-SKSU</title>
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
                    <table width="100%" border="1" style="border-right: 1px solid black">
                        <thead>
                            <tr>
                                <th style="font-weight: bold;" bgcolor="yellow" colspan="4" align="center">Siap Kerja
                                </th>
                            </tr>

                            <tr>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">No</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Profil Lulusan
                                </th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Kualifikasi</th>
                                <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Kompetensi Kerja
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siapKerja as $index => $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid black;">{{ $item->profil_lulusan }}</td>
                                    <td style="border: 1px solid black;">{{ $item->kualifikasi }}</td>
                                    <td style="border: 1px solid black;">{{ $item->kompetensi_kerja }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table>
                        <tr></tr>
                        <tr></tr>
                    </table>

                    <table width="100%" border="1">
                        <thead>
                            <tr>
                                <th colspan="4" bgcolor="yellow">Siap Usaha</th>
                            </tr>

                            <tr>
                                <th style="border: 1px solid black; font-weight:bold" bgcolor="gray">No</th>
                                <th style="border: 1px solid black; font-weight:bold" bgcolor="gray">Profil Lulusan</th>
                                <th style="border: 1px solid black; font-weight:bold" bgcolor="gray">Kualifikasi</th>
                                <th style="border: 1px solid black; font-weight:bold" bgcolor="gray">Kompetensi Kerja
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siapUsaha as $index => $item)
                                <tr>
                                    <td style="border: 1px solid  black">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid  black">{{ $item->profil_lulusan }}</td>
                                    <td style="border: 1px solid  black">{{ $item->kualifikasi }}</td>
                                    <td style="border: 1px solid  black">{{ $item->kompetensi_kerja }}</td>
                                </tr>
                            @endforeach
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
