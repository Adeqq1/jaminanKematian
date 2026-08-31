<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Pengajuan Klaim</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #17252f; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .page { padding: 34px 42px; }
        .header { border-bottom: 3px solid #16865c; padding-bottom: 16px; }
        .eyebrow { color: #16865c; font-size: 9px; font-weight: bold; letter-spacing: 1.4px; text-transform: uppercase; }
        h1 { color: #123b5d; font-size: 22px; margin: 8px 0 4px; }
        h2 { color: #123b5d; font-size: 13px; margin: 0 0 10px; }
        .subtitle { color: #5c6b75; font-size: 10px; }
        .status { background: #eaf8f0; border: 1px solid #bfe3d3; color: #126b4b; font-weight: bold; padding: 8px 10px; }
        .ticket { background: #f1f7f9; border: 1px solid #c8e2ed; margin: 22px 0; padding: 18px; }
        .ticket-table, .data-table { border-collapse: collapse; width: 100%; }
        .ticket-table td { vertical-align: middle; }
        .number-label { color: #39725b; font-size: 10px; }
        .number { color: #123b5d; font-size: 48px; font-weight: bold; line-height: 1; }
        .ticket-info { color: #536673; font-size: 10px; line-height: 1.7; text-align: right; }
        .section { margin-top: 18px; }
        .data-table td { border-bottom: 1px solid #e1eaee; padding: 7px 0; vertical-align: top; }
        .data-table td:first-child { color: #5c6b75; width: 35%; }
        .data-table td:last-child { font-weight: bold; }
        .columns { width: 100%; }
        .columns td { padding-right: 24px; vertical-align: top; width: 50%; }
        .columns td:last-child { padding-right: 0; }
        .instructions { background: #fff8e8; border-left: 4px solid #d59628; line-height: 1.6; margin-top: 20px; padding: 12px 14px; }
        .documents { margin-top: 18px; }
        .documents td { border-bottom: 1px solid #e1eaee; padding: 7px 0; }
        .document-page { color: #5c6b75; text-align: right; }
        .footer { border-top: 1px solid #d9e1e6; color: #657680; font-size: 8px; margin-top: 22px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="eyebrow">Pelayanan publik Kabupaten Bungo</div>
            <h1>Pelayanan Klaim Jaminan Kematian</h1>
            <div class="subtitle">Bukti pengajuan dan nomor antrean layanan</div>
        </div>

        <div class="status" style="margin-top: 18px;">Pengajuan diterima. Simpan dokumen ini dan tunjukkan kepada petugas.</div>

        <div class="ticket">
            <table class="ticket-table">
                <tr>
                    <td><div class="number-label">Nomor antrean</div><div class="number">{{ $pengajuan->nomorAntrian->nomor_urut }}</div></td>
                    <td class="ticket-info"><strong>{{ $pengajuan->nomorAntrian->jenis_pelayanan }}</strong><br>{{ $pengajuan->nomorAntrian->nomor_loket }}<br>Berlaku {{ $pengajuan->nomorAntrian->tanggal_antrian->format('d F Y') }}<br>Diambil {{ $pengajuan->nomorAntrian->waktu_pengambilan->format('d-m-Y H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        <table class="columns">
            <tr>
                <td><div class="section"><h2>Data peserta</h2><table class="data-table"><tr><td>Nama</td><td>{{ $pengajuan->peserta->nama }}</td></tr><tr><td>NIK</td><td>{{ $pengajuan->peserta->nik }}</td></tr><tr><td>Nomor HP</td><td>{{ $pengajuan->no_hp_peserta }}</td></tr></table></div></td>
                <td><div class="section"><h2>Data ahli waris</h2><table class="data-table"><tr><td>Nama</td><td>{{ $pengajuan->nama_ahli_waris }}</td></tr><tr><td>Nomor HP</td><td>{{ $pengajuan->no_hp_ahli_waris }}</td></tr><tr><td>Bank</td><td>{{ $pengajuan->nama_bank ?? 'Belum tercatat' }}</td></tr><tr><td>Rekening</td><td>{{ $pengajuan->nomor_rekening_ahli_waris }}</td></tr></table></div></td>
            </tr>
        </table>

        <div class="section"><h2>Keterangan ahli waris</h2><div class="subtitle">{{ $pengajuan->keterangan_ahli_waris }}</div></div>
        <div class="instructions"><strong>Saat datang ke kantor</strong><br>Bawa dokumen asli: KTP peserta, akta kematian, kartu BPJS, dan buku rekening ahli waris.<br>Jl. Sultan Thaha No.111, Bungo Barat, Muara Bungo, Jambi 37211 · 08.00–16.00 WIB · +62 813-6184-563</div>

        <div class="documents"><h2>Dokumen pendukung dalam file ini</h2><table class="data-table">@foreach ($documentPages as $label => $range)<tr><td>{{ $label }}</td><td class="document-page">Halaman {{ $range[0] }}@if ($range[0] !== $range[1])–{{ $range[1] }}@endif</td></tr>@endforeach</table></div>
        <div class="footer">Nomor pengajuan: {{ $pengajuan->id }} · Dokumen rahasia, hanya untuk keperluan pelayanan. Jangan membagikan file ini kepada pihak yang tidak berwenang.</div>
    </div>
</body>
</html>
