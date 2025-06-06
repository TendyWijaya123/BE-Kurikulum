<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>13A_Organisasi MK</title>
    </head>

    <body>
        @php
            $colLength = array_sum($maxPerKategori) + 8;
            $totalRatio = 7;
            $leftColspan = floor((4 / $totalRatio) * $colLength);
            $middleColspan = floor((2 / $totalRatio) * $colLength);
            $rightColspan = $colLength - ($leftColspan + $middleColspan);
        @endphp

        <tr>
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 13 A</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Organisasi Mata Kuliah' }}
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
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">Semester
                                </th>
                                <th rowspan="2"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">Total
                                    SKS</th>
                                <th rowspan="2"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">Jumlah
                                    Mata Kuliah</th>
                                <th colspan="{{ array_sum($maxPerKategori) }}"
                                    style="border: 1px solid black; background-color: gray; font-weight: bold;">
                                    Daftar Mata Kuliah
                                </th>
                            </tr>
                            <tr>
                                @foreach ($maxPerKategori as $kategori => $jumlah)
                                    <th colspan="{{ $jumlah }}"
                                        style="border: 1px solid black; background-color: {{ $kategori ? 'gray' : 'red' }}; font-weight: bold;">
                                        {{ $kategori ?: 'Belum Diisi' }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($mataKuliahBySemester as $semester => $data)
                                <tr>
                                    <td rowspan="2"
                                        style="border: 1px solid black; background-color: {{ $semester ? 'white' : 'red' }};">
                                        {{ $semester ?: 'Belum Diisi Semester' }}
                                    </td>
                                    <td rowspan="2" style="border: 1px solid black;">{{ $data['total_sks'] }}</td>
                                    <td rowspan="2" style="border: 1px solid black;">
                                        {{ $data['jumlah_mata_kuliah'] }}</td>

                                    @foreach ($maxPerKategori as $kategori => $max)
                                        @php
                                            $mataKuliahKategori = $data['kategori'][$kategori]['mata_kuliah'] ?? [];
                                        @endphp

                                        @for ($i = 0; $i < $max; $i++)
                                            @php
                                                $jenjang = $prodi['jenjang'] ?? null;
                                                $backgroundColor = '#F8CBAD'; // default

                                                if (
                                                    ($jenjang === 'D3' && $semester == 5) ||
                                                    ($jenjang === 'D4' && $semester == 6)
                                                ) {
                                                    $backgroundColor = 'yellow';
                                                } elseif ($semester == 7 && $semester == 4) {
                                                    $backgroundColor = '#6D9EEB';
                                                }
                                            @endphp
                                            <td
                                                style="border: 1px solid black; background-color: {{ $backgroundColor }}">
                                                {{ $mataKuliahKategori[$i]['nama'] ?? '' }}
                                            </td>
                                        @endfor
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($maxPerKategori as $kategori => $max)
                                        @php
                                            $mataKuliahKategori = $data['kategori'][$kategori]['mata_kuliah'] ?? [];
                                        @endphp

                                        @for ($i = 0; $i < $max; $i++)
                                            <td style="border: 1px solid black;">
                                                {{ isset($mataKuliahKategori[$i]['sks']) ? $mataKuliahKategori[$i]['sks'] . ' sks' : '' }}
                                            </td>
                                        @endfor
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <td style="font-weight: bold; background-color: lightgray; border: 1px solid black;">
                                    Total</td>
                                <td style="border: 1px solid black;">{{ $totalKeseluruhan['total_sks'] }}</td>
                                <td style="border: 1px solid black;">{{ $totalKeseluruhan['jumlah_mata_kuliah'] }}</td>
                                <td colspan="{{ array_sum($maxPerKategori) }}" style="border: 1px solid black;"></td>
                            </tr>
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
