<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 12px;
            padding-bottom: 6px;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-logo-cell {
            width: 75px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo {
            width: 60px;
            height: auto;
        }
        .kop-text-cell {
            text-align: center;
            vertical-align: middle;
        }
        .kop-spacer-cell {
            width: 75px;
        }
        .kop-instansi {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-kecamatan {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 2px 0;
        }
        .kop-alamat {
            font-size: 8pt;
            color: #222;
            margin: 0;
        }
        .judul-laporan {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 12px 0 4px 0;
        }
        .sub-judul {
            text-align: center;
            font-size: 8.5pt;
            margin-bottom: 12px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #333;
            padding: 5px 6px;
            font-size: 8pt;
        }
        table.data-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .tanda-tangan {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .ttd-box {
            float: right;
            width: 250px;
            text-align: center;
            font-size: 8.5pt;
        }
        .ttd-space {
            height: 55px;
        }
    </style>
</head>
<body>

    <!-- Kop Kedinasan -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                <img src="{{ public_path('logo/tala.png') }}" class="kop-logo" alt="Logo Kabupaten Tanah Laut">
            </td>
            <td class="kop-text-cell">
                <div class="kop-instansi">Pemerintah Kabupaten Tanah Laut</div>
                <div class="kop-kecamatan">Kecamatan Bati-Bati</div>
                <div class="kop-alamat">Jl. Ahmad Yani Km. 38, Bati-Bati, Kabupaten Tanah Laut, Kalimantan Selatan 70852</div>
            </td>
            <td class="kop-spacer-cell"></td>
        </tr>
    </table>

    <!-- Judul Laporan -->
    <div class="judul-laporan">{{ $judulLaporan }}</div>
    <div class="sub-judul">
        Periode: {{ $periodeText }} | Wilayah: {{ $desa ? 'Desa ' . $desa->nama_desa : 'Seluruh Desa di Wilayah Kecamatan Bati-Bati' }}
    </div>

    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 95px;">Kode Kematian</th>
                <th style="width: 110px;">NIK Almarhum</th>
                <th>Nama Almarhum/Almarhumah</th>
                <th style="width: 25px;">JK</th>
                <th style="width: 95px;">Desa</th>
                <th style="width: 80px;">Tgl Meninggal</th>
                <th style="width: 150px;">Tempat Meninggal</th>
                <th>Penyebab Kematian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono text-center">{{ $row->kode_kematian }}</td>
                    <td class="font-mono text-center">{{ $row->penduduk->nik ?? '-' }}</td>
                    <td><strong>{{ $row->penduduk->nama_lengkap ?? '-' }}</strong></td>
                    <td class="text-center">{{ $row->penduduk->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $row->penduduk->desa->nama_desa ?? '-' }}</td>
                    <td class="text-center">{{ $row->tanggal_meninggal ? $row->tanggal_meninggal->format('d/m/Y') : '-' }}</td>
                    <td>{{ $row->tempat_meninggal }}</td>
                    <td>{{ $row->sebab_meninggal ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #666;">
                        Tidak ada catatan kematian yang sesuai kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 8px; font-size: 8pt; color: #444;">
        <em>Total Mutasi Meninggal: {{ $data->count() }} Jiwa</em>
    </div>

    <!-- Blok Tanda Tangan -->
    <div class="tanda-tangan">
        <div class="ttd-box">
            <div>Bati-Bati, {{ $tanggalCetak }}</div>
            <div>Mengetahui,</div>
            <div style="font-weight: bold; margin-bottom: 2px;">Camat Bati-Bati</div>
            <div class="ttd-space"></div>
            <div style="font-weight: bold; text-decoration: underline;">ADE GUMILAR, S.STP</div>
            <div>Pembina Tk. I (IV/b)</div>
            <div>NIP. 19800512 200012 1 002</div>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
