<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>10_Matriks P-MP</title>
    </head>

    <body>
        @php
            $colLength = count($pengetahuans) + 4;
            $totalRatio = 7;
            $leftColspan = floor((4 / $totalRatio) * $colLength);
            $middleColspan = floor((2 / $totalRatio) * $colLength);
            $rightColspan = $colLength - ($leftColspan + $middleColspan);
        @endphp

        <tr>
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 10</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Matriks Materi Pembelajaran dengan Pengetahuan' }}
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


                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">No</th>
                                <th colspan="2"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">Materi
                                    Pembelajaran (MP)</th>
                                <th colspan="{{ count($pengetahuans) }}"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                    Pengetahuan (P)</th>
                            </tr>
                            <tr>
                                <th style="border: 1px solid black; background-color: gray; font-weight: bold;">Kode
                                </th>
                                <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                    Deskripsi</th>
                                @foreach ($pengetahuans as $pengetahuan)
                                    <th style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                        {{ $pengetahuan->kode_pengetahuan }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materiPembelajarans as $index => $materiPembelajaran)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid black;">{{ $materiPembelajaran->code }}</td>
                                    <td style="border: 1px solid black;">{{ $materiPembelajaran->description }}</td>

                                    @foreach ($pengetahuans as $pengetahuan)
                                        <td style="border: 1px solid black;">
                                            {{ $pengetahuan->mp->contains($materiPembelajaran->id) ? '✔' : '' }}
                                        </td>
                                    @endforeach
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
