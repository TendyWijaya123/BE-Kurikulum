<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>16_Pemilahan MK</title>
    </head>

    <body>
        @foreach ($mataKuliahByKategori as $data)
            <table>
                <thead>
                    <tr>
                        <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray" colspan="2">Kode
                            Matakuliah</th>
                        <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">Mata Kuliah</th>
                        <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">SKS</th>
                        <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">SKS Teori</th>
                        <th style="border: 1px solid black; font-weight:bold;" bgcolor="gray">SKS Praktik</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowspan = count($data['mata_kuliah']) + 1; @endphp
                    @foreach ($data['mata_kuliah'] as $i => $mataKuliah)
                        <tr>
                            @if ($i === 0)
                                <td rowspan="{{ $rowspan }}"
                                    @if (empty($data['kategori'])) bgcolor="red" @endif>
                                    {{ !empty($data['kategori']) ? 'Mata Kuliah ' . $data['kategori'] : 'Kategori Belum Diisi' }}
                                </td>
                            @endif
                            <td>{{ $mataKuliah['kode'] }}</td>
                            <td>{{ $mataKuliah['nama'] }}</td>
                            <td>{{ $mataKuliah['total_praktek'] + $mataKuliah['total_teori'] }}</td>
                            <td>{{ $mataKuliah['total_teori'] }}</td>
                            <td>{{ $mataKuliah['total_praktek'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">Sub Jumlah</td>
                        <td>{{ $data['total']['total_kategori'] }}</td>
                        <td>{{ $data['total']['total_teori_kategori'] }}</td>
                        <td>{{ $data['total']['total_praktek_kategori'] }}</td>
                    </tr>
                </tbody>

            </table>
        @endforeach
    </body>

</html>
