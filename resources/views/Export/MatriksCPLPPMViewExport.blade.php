<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>7_Matriks PPM-CPL</title>
    </head>

    <body>

        <table>
            <thead>
                <tr>
                    <th>Profil Profesional Mandiri
                    </th>
                    @foreach ($cpls as $cpl)
                        <th>{{ $cpl->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($ppms as $ppm)
                    <tr>
                        <td>{{ $ppm->kode }}</td>
                        @foreach ($cpls as $cpl)
                            <td>{{ $cpl->ppms->contains($ppm->id) ? '✔' : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
