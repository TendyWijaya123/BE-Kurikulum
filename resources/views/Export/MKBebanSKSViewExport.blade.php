<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>12B_MK_BebanSKS</title>
    </head>

    <body>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Tujuan Belajar</th>
                    <th>Formulasi (C, P, A)</th>
                    <th>Beban Belajar Mahasiswa (jam/smt)</th>
                    <th>SKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mataKuliah as $index => $mk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mk->kode }}</td>
                        <td>{{ $mk->nama }}</td>
                        <td>{{ $mk->tujuan }}</td>
                        <td>
                            @if (!empty($mk->formulasiCpas) && is_iterable($mk->formulasiCpas))
                                {{ implode(', ', array_column($mk->formulasiCpas->toArray(), 'kode')) }}
                            @else
                            @endif
                        </td>
                        <td>{{ $mk->kemampuan_akhirs_sum_estimasi_beban_belajar ?? '0' }}</td>
                        <td>{{ $mk->sks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>

</html>
