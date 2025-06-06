<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>16_Pemilahan MK</title>
    </head>

    <body>
        @php
            $colLength = 7 + 4;
            $totalRatio = 7;
            $leftColspan = floor((4 / $totalRatio) * $colLength);
            $middleColspan = floor((2 / $totalRatio) * $colLength);
            $rightColspan = $colLength - ($leftColspan + $middleColspan);
        @endphp

        <tr>
            <td align="center" colspan={{ $colLength }} style="font-weight: bold; font-size:13px">LAMPIRAN 16</td>
        </tr>
        <tr></tr>

        <table style="border:1px solid black; border-collapse: collapse;">
            <tr>
                <td colspan="{{ $leftColspan }}" rowspan="2" align="center"
                    style="border: 1px solid  black; font-weight:bold; font-size:13px">
                    {{ 'Pemilahan Mata Kuliah' }}
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


                    @foreach ($mataKuliahByKategori as $data)
                        <table style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black; font-weight: bold; background-color: gray;"
                                        colspan="2">Kode Matakuliah</th>
                                    <th style="border: 1px solid black; font-weight: bold; background-color: gray;">Mata
                                        Kuliah</th>
                                    <th style="border: 1px solid black; font-weight: bold; background-color: gray;">SKS
                                    </th>
                                    <th style="border: 1px solid black; font-weight: bold; background-color: gray;">SKS
                                        Teori</th>
                                    <th style="border: 1px solid black; font-weight: bold; background-color: gray;">SKS
                                        Praktik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $rowspan = count($data['mata_kuliah']) + 1; @endphp
                                @foreach ($data['mata_kuliah'] as $i => $mataKuliah)
                                    <tr>
                                        @if ($i === 0)
                                            <td rowspan="{{ $rowspan }}"
                                                style="border: 1px solid black; {{ empty($data['kategori']) ? 'background-color: red;' : '' }}">
                                                {{ !empty($data['kategori']) ? 'Mata Kuliah ' . $data['kategori'] : 'Kategori Belum Diisi' }}
                                            </td>
                                        @endif
                                        <td style="border: 1px solid black;">{{ $mataKuliah['kode'] }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah['nama'] }}</td>
                                        <td style="border: 1px solid black;">
                                            {{ $mataKuliah['total_praktek'] + $mataKuliah['total_teori'] }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah['total_teori'] }}</td>
                                        <td style="border: 1px solid black;">{{ $mataKuliah['total_praktek'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" style="border: 1px solid black; font-weight: bold;">Sub Jumlah
                                    </td>
                                    <td style="border: 1px solid black;">{{ $data['total']['total_kategori'] }}</td>
                                    <td style="border: 1px solid black;">{{ $data['total']['total_teori_kategori'] }}
                                    </td>
                                    <td style="border: 1px solid black;">{{ $data['total']['total_praktek_kategori'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 1px solid black; font-weight: bold;">Persentase
                                        SKS
                                    </td>
                                    <td colspan="3" style="border: 1px solid black; font-weight: bold;">
                                        {{ round(($data['total']['total_kategori'] / $totalKeseluruhan['total_kurikulum']) * 100) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tr></tr>
                        </table>
                    @endforeach

                    <table>
                        <tr></tr>
                    </table>
                    <table>
                        <tr>
                            <td colspan="3" style="border: 1px solid black; font-weight: bold;">JUMLAH</td>
                            <td style="border: 1px solid black;">{{ $totalKeseluruhan['total_kurikulum'] }}</td>
                            <td style="border: 1px solid black;">{{ $totalKeseluruhan['total_teori'] }}</td>
                            <td style="border: 1px solid black;">{{ $totalKeseluruhan['total_praktek'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border: 1px solid black; font-weight: bold;">PERSENTASE</td>
                            <td style="border: 1px solid black;">100%</td>
                            <td style="border: 1px solid black;">
                                {{ round(($totalKeseluruhan['total_teori'] / $totalKeseluruhan['total_kurikulum']) * 100, 2) }}%
                            </td>
                            <td style="border: 1px solid black;">
                                {{ round(($totalKeseluruhan['total_praktek'] / $totalKeseluruhan['total_kurikulum']) * 100, 2) }}%
                            </td>
                        </tr>
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
