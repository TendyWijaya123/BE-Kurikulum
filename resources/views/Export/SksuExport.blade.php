<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>4d_AK-KKNI</title>
    </head>

    <body>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th bgcolor="yellow" colspan="4">Rancangan CPL Prodi Setelah AK4(penyelarasan dengan level KKNI)
                    </th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Profil Lulusan</th>
                    <th bgcolor="gray">Kualifikasi</th>
                    <th bgcolor="gray">Kompetensi Kerja</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siapKerja as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->profil_lulusan }}</td>
                        <td>{{ $item->kualifikasi }}</td>
                        <td>{{ $item->kompetensi_kerja }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <br>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th colspan="4" bgcolor="yellow">Siap Usaha</th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Profil Lulusan</th>
                    <th bgcolor="gray">Kualifikasi</th>
                    <th bgcolor="gray">Kompetensi Kerja</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siapUsaha as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->profil_lulusan }}</td>
                        <td>{{ $item->kualifikasi }}</td>
                        <td>{{ $item->kompetensi_kerja }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
