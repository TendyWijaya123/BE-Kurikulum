<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>4a_AK_BK</title>
    </head>

    <body>

        <table width="100%" border="1">
            <thead>
                <tr>
                    <th bgcolor="gray">No</th>
                    <th bgcolor="gray">Program Studi</th>
                    <th bgcolor="gray">Daftar CPL</th>
                    <th bgcolor="gray">PPM </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4">Luar Negeri</td>
                </tr>
                @foreach ($benchKurikulumLuarNegeri as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->program_studi }}</td>
                        <td>{{ $item->cpl }}</td>
                        <td>{{ $item->ppm }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="4">Dalam Negeri</td>
                </tr>
                @foreach ($benchKurikulumDalamNegeri as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->program_studi }}</td>
                        <td>{{ $item->cpl }}</td>
                        <td>{{ $item->ppm }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>



    </body>

</html>
