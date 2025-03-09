<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>9_Matriks P-CPL</title>
    </head>

    <body>

        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Pengetahuan</th>
                    <th colspan="{{ count($cpls) }}">CPL</th>
                </tr>
                <tr>
                    @foreach ($cpls as $item)
                        <th>{{ $item->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($pengetahuans as $index => $pengetahuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengetahuan->kode_pengetahuan . '-' . $pengetahuan->deskripsi }}</td>
                        @foreach ($cpls as $item)
                            <td>{{ $pengetahuan->cpls->contains($item->id) ? '✔' : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
