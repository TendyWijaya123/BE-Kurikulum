<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>8_Matriks CPL-IEA</title>
    </head>

    <body>

        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">CPL</th>
                    <th colspan="{{ count($iea) }}">CPL Berdasarkan IEA</th>
                </tr>
                <tr>
                    @foreach ($iea as $item)
                        <th>{{ $item->code }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($cpls as $index => $cpl)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cpl->kode }}</td>
                        @foreach ($iea as $item)
                            <td>{{ $cpl->iea->contains($item->id) ? '✔' : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
