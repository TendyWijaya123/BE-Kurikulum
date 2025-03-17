<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>10_Matriks P-MP</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid black;
                padding: 8px;
                text-align: center;
            }

            th {
                background-color: yellow;
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th colspan="2">Materi Pembelajaran (MP)</th>
                    <th colspan="{{ count($pengetahuans) }}">Pengetahuan (P)</th>
                </tr>
                <tr>
                    <th>Kode</th>
                    <th>Deskripsi</th>
                    @foreach ($pengetahuans as $pengetahuan)
                        <th>{{ $pengetahuan->kode_pengetahuan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($materiPembelajarans as $index => $materiPembelajaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $materiPembelajaran->code }}</td>
                        <td>{{ $materiPembelajaran->description }}</td>

                        @foreach ($pengetahuans as $pengetahuan)
                            <td>{{ $pengetahuan->mp->contains($materiPembelajaran->id) ? '✔' : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
