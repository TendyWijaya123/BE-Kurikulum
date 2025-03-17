<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>11_Matriks P-MP-MK</title>
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
                    <th>Materi Pembelajaran (MP) </th>
                    <th>Cognitif Process Dimensions</th>
                    @foreach ($pengetahuans as $pengetahuan)
                        <th>{{ $pengetahuan->kode_pengetahuan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($materiPembelajarans as $index => $materiPembelajaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $materiPembelajaran->description }}</td>
                        <td>{{ $materiPembelajaran->cognitif_proses }}</td>

                        @foreach ($pengetahuans as $pengetahuan)
                            <td>
                                @php
                                    $mp = $pengetahuan->mps->firstWhere('mp_id', $materiPembelajaran->id);
                                @endphp

                                @if ($mp && isset($mp->mataKuliahs))
                                    {!! $mp->mataKuliahs->pluck('nama')->implode('<br>') !!}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
