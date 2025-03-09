<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>4c_AK-IPTEKS</title>
    </head>

    <body>
        <table width="100%" border="1">
            <thead>
                <tr>
                    <th bgcolor="gray">Ilmu Pengetahuan</th>
                    <th bgcolor="gray">Teknologi</th>
                    <th bgcolor="gray">Seni</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxRows = max(count($ipteksPengetahuan), count($ipteksTeknologi), count($ipteksSeni));
                @endphp

                @for ($i = 0; $i < $maxRows; $i++)
                    <tr>
                        <td>{{ $ipteksPengetahuan[$i]->deskripsi ?? '' }}</td>
                        <td>{{ $ipteksTeknologi[$i]->deskripsi ?? '' }}</td>
                        <td>{{ $ipteksSeni[$i]->deskripsi ?? '' }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </body>

</html>
