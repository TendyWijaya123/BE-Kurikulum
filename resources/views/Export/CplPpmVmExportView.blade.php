<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>6_CPL-PPM-VM</title>
    </head>

    <body>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th bgcolor="yellow" colspan="3">Rancangan CPL Prodi Setelah AK4(penyelarasan dengan level KKNI)
                    </th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Daftar CPL</th>
                    <th bgcolor="gray">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cpl as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th colspan="3" bgcolor="yellow">Peran di Industri (Setelah Bekerja 3 s/d 5 tahun)
                    </th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Jabatan</th>
                    <th bgcolor="gray">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peranIndustri as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th colspan="3" bgcolor="yellow">Profil Profesional Mandiri (PPM) Program Studi (Setelah
                        Penyelarasan dengan Lampiran 5)
                    </th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Daftar PPM</th>
                    <th bgcolor="gray">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ppm as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
