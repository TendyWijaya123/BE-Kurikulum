<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>5_VMT</title>
    </head>

    <body>

        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Polban</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 500px;">{{ $vmtPolban->visi_polban }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">Misi Polban</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($misiPolban as $index => $item)
                    <tr>
                        <td style="width: 500px;">{{ $index + 1 . '. ' . $item->misi_polban }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">Tujuan Polban</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tujuanPolban as $index => $item)
                    <tr>
                        <td style="width: 500px;">{{ $index + 1 . '. ' . $item->tujuan_polban }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Jurusan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 500px;">{{ $vmtJurusan->visi_jurusan }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">MisiJurusan Polban</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($misiJurusan as $index => $item)
                    <tr>
                        <td style="width: 500px;">{{ $index + 1 . '. ' . $item->misi_jurusan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 500px; font-size: 15px; font-weight: bold;">Visi Keilmuan Prodi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 500px;">{{ $vmtJurusan->visi_keilmuan_prodi }}</td>
                </tr>
            </tbody>
        </table>
    </body>

</html>
