<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>5_VMT</title>
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
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 5</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Visi, Misi, Sasaran, Tujuan, Strategi dan Tata Nilai PTV' }}
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
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Polban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 500px;">{{ $vmtPolban->visi_polban }}</td>
                            </tr>
                            <tr></tr>
                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">Misi Polban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($misiPolban as $index => $item)
                                <tr>
                                    <td style="width: 500px;">{{ $index + 1 . '. ' . $item->misi_polban }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>

                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">Tujuan Polban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tujuanPolban as $index => $item)
                                <tr>
                                    <td style="width: 500px;">{{ $index + 1 . '. ' . $item->tujuan_polban }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>

                        </tbody>
                    </table>



                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 500px;">{{ $vmtJurusan->visi_jurusan }}</td>
                            </tr>
                            <tr></tr>

                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">MisiJurusan Polban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($misiJurusan as $index => $item)
                                <tr>
                                    <td style="width: 500px;">{{ $index + 1 . '. ' . $item->misi_jurusan }}</td>
                                </tr>
                            @endforeach
                            <tr></tr>

                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Keilmuan Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 500px;">{{ $vmtJurusan->visi_keilmuan_prodi }}</td>
                            </tr>
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
