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
                    <th bgcolor="yellow" colspan="3">Rancangan CPL Prodi Setelah AK4(penyelarasan dengan level KKNI)
                    </th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Daftar CPL</th>
                    <th bgcolor="gray">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cplKkni as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>



    </body>

</html>
