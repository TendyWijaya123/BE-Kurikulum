<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>15_Sebaran MK</title>
    </head>

    <body>
        @php
            $colLength = 15;
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
                    @foreach ($mataKuliahBySemester as $data)
                        <table>
                            <thead>
                                <tr>
                                    @if (empty($data['semester']))
                                        <td bgcolor="red" colspan="13">Semester Belum Diisi</td>
                                    @else
                                        <td bgcolor="yellow" colspan="13">SEMESTER {{ $data['semester'] }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="border: 1px solid black;" align="center" rowspan="3" bgcolor="gray">
                                        NO.</th>
                                    <th style="border: 1px solid black;" align="center" rowspan="3" bgcolor="gray">
                                        KODE MK</th>
                                    <th style="border: 1px solid black;" align="center" rowspan="3" bgcolor="gray">
                                        MATA KULIAH
                                    </th>
                                    <th style="border: 1px solid black;" align="center" rowspan="1" colspan="3"
                                        bgcolor="gray">SKS</th>
                                    <th style="border: 1px solid black;" align="center" rowspan="1" colspan="7"
                                        bgcolor="gray">Beban Belajar (menit/minggu)
                                    </th>
                                </tr>

                                <tr>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="2">Teori
                                    </th>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="2">Praktek</th>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="2">Total</th>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="1" colspan="3">Teori</th>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="1" colspan="3">Praktek</th>
                                    <th style="border: 1px solid black;  font-weight:bold;" bgcolor="gray"
                                        align="center" rowspan="2">Total</th>
                                </tr>

                                <tr>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray" align="center">
                                        BT</th>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray" align="center">
                                        PT</th>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray" align="center">
                                        M</th>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray" align="center">
                                        BT</th>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray" align="center">
                                        PT</th>
                                    <th style="border: 1px solid black; font-weight:bold" bgcolor="gray"
                                        align="center">M</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['mata_kuliah'] as $index => $mataKuliah)
                                    <tr>
                                        <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->kode }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->nama }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->total_teori }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->total_praktek }}</td>
                                        <td style="border: 1px solid black;">
                                            {{ $mataKuliah->total_teori + $mataKuliah->total_praktek }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->teori_bt }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->teori_pt }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->teori_m }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->praktek_bt }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->praktek_pt }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah->praktek_m }}</td>
                                        <td style="border: 1px solid black;">
                                            {{ $mataKuliah->teori_bt +
                                                $mataKuliah->teori_pt +
                                                $mataKuliah->teori_m +
                                                $mataKuliah->praktek_bt +
                                                $mataKuliah->praktek_pt +
                                                $mataKuliah->praktek_m }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td style="border: 1px solid black;" colspan="3"><strong>JUMLAH</strong></td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['total_teori_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['total_praktek_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['total_teori_semester'] + $data['total']['total_praktek_semester'] }}</strong>
                                    </td>

                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['teori_bt_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['teori_pt_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['teori_m_semester'] }}</strong>
                                    </td>

                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['praktek_bt_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['praktek_pt_semester'] }}</strong>
                                    </td>
                                    <td style="border: 1px solid black;">
                                        <strong>{{ $data['total']['praktek_m_semester'] }}</strong>
                                    </td>

                                    <td style="border: 1px solid black;"><strong>
                                            {{ $data['total']['teori_bt_semester'] +
                                                $data['total']['teori_pt_semester'] +
                                                $data['total']['teori_m_semester'] +
                                                $data['total']['praktek_bt_semester'] +
                                                $data['total']['praktek_pt_semester'] +
                                                $data['total']['praktek_m_semester'] }}
                                        </strong></td>
                                </tr>

                            </tbody>

                        </table>

                        <table>
                            <tr></tr>
                        </table>
                    @endforeach

                    <table>
                        <thead>
                            <tr>
                                <th bgcolor="gray" colspan="3" style="border: 1px solid black; font-weight:bold;">
                                    SKS</th>
                                <th bgcolor="gray" colspan="3" style="border: 1px solid black; font-weight:bold;">
                                    BEBAN
                                    BELAJAR
                                    (menit/minggu)</th>
                            </tr>
                            <tr>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">TEORI</th>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">PRAKTIK</th>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">JUMLAH</th>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">TEORI</th>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">PRAKTIK</th>
                                <th bgcolor="gray" style="border: 1px solid black; font-weight:bold;">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black">{{ $totalKeseluruhan['total_teori_sks'] }}</td>
                                <td style="border: 1px solid black">{{ $totalKeseluruhan['total_praktek_sks'] }}</td>
                                <td style="border: 1px solid black">
                                    {{ $totalKeseluruhan['total_sks'] }}</td>
                                <td style="border: 1px solid black">{{ $totalKeseluruhan['total_teori_menit'] }}</td>
                                <td style="border: 1px solid black">{{ $totalKeseluruhan['total_praktek_menit'] }}
                                </td>
                                <td style="border: 1px solid black">{{ $totalKeseluruhan['total_menit'] }}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black">
                                    {{ round(($totalKeseluruhan['total_teori_sks'] / $totalKeseluruhan['total_sks']) * 100) }}%
                                </td>
                                <td style="border: 1px solid black">
                                    {{ round(($totalKeseluruhan['total_praktek_sks'] / $totalKeseluruhan['total_sks']) * 100) }}%
                                </td>
                                <td style="border: 1px solid black">100%</td>
                                <td style="border: 1px solid black">
                                    {{ round(($totalKeseluruhan['total_teori_menit'] / $totalKeseluruhan['total_menit']) * 100) }}%
                                </td>
                                <td style="border: 1px solid black">
                                    {{ round(($totalKeseluruhan['total_praktek_menit'] / $totalKeseluruhan['total_menit']) * 100) }}%
                                </td>
                                <td style="border: 1px solid black">100%</td>
                            </tr>

                        </tbody>
                    </table>
                    {{-- KONTEN END --}}
                </td>
            </tr>



            {{-- FOOTER START --}}
            <tr>
                <td colspan="{{ floor(($colLength * 3) / 7) }}" style="background-color: orange;">No Rev:001</td>
                <td colspan="{{ floor(($colLength * 2) / 7) }}" style="background-color: orange;">Tgl. Berlaku:
                    Agustus
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
