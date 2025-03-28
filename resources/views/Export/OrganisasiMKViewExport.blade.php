<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>13A_Organisasi_MK</title>
        <style>
            table,
            th,
            td {
                border: 1px solid black;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 5px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <table>
            <thead>
                <tr style="background-color: gray; font-weight: bold; border: 1px solid black;">
                    <th rowspan="2" style="border: 1px solid black; background-color: gray;">Semester</th>
                    <th rowspan="2" style="border: 1px solid black; background-color: gray;">Total SKS</th>
                    <th rowspan="2" style="border: 1px solid black; background-color: gray;">Jumlah Mata Kuliah</th>
                    <th colspan="{{ array_sum($maxPerKategori) }}"
                        style="border: 1px solid black; background-color: gray;">Daftar Mata Kuliah
                    </th>
                </tr>
                <tr style=" font-weight: bold; border: 1px solid black; ">
                    @foreach ($maxPerKategori as $kategori => $jumlah)
                        <th colspan="{{ $jumlah }}"
                            style="border: 1px solid black; background-color: {{ $kategori ? 'gray' : 'red' }};">
                            {{ $kategori ?: 'Belum Diisi' }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($mataKuliahBySemester as $semester => $data)
                    <tr>
                        <td rowspan="2" style="background-color: {{ $semester ? 'white' : 'red' }};">
                            {{ $semester ?: 'Belum Diisi Semester' }}
                        </td>
                        <td rowspan="2" style="border: 1px solid black;">{{ $data['total_sks'] }}</td>
                        <td rowspan="2" style="border: 1px solid black;">{{ $data['jumlah_mata_kuliah'] }}</td>

                        @foreach ($maxPerKategori as $kategori => $max)
                            @php
                                $mataKuliahKategori = $data['kategori'][$kategori]['mata_kuliah'] ?? [];
                            @endphp

                            @for ($i = 0; $i < $max; $i++)
                                <td style="border: 1px solid black;">
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
                    <td style="font-weight: bold; background-color: lightgray; border: 1px solid black;">Total</td>
                    <td style="border: 1px solid black;">{{ $totalKeseluruhan['total_sks'] }}</td>
                    <td style="border: 1px solid black;">{{ $totalKeseluruhan['jumlah_mata_kuliah'] }}</td>
                    <td style="border: 1px solid black;"></td>
                </tr>
            </tbody>

        </table>
    </body>

</html>
