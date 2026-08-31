<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanKlaimRequest;
use App\Models\NomorAntrian;
use App\Models\PengajuanKlaim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PengajuanKlaimController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.pengajuan.index');
        }
        $peserta = $user->peserta()->with(['pengajuanKlaim.nomorAntrian'])->firstOrFail();

        return Inertia::render('Dashboard', ['peserta' => $peserta, 'pengajuan' => $peserta->pengajuanKlaim]);
    }

    public function store(StorePengajuanKlaimRequest $request)
    {
        $peserta = $request->user()->peserta;
        abort_unless($peserta && ! $peserta->pengajuanKlaim()->exists(), 422, 'Pengajuan klaim sudah pernah dibuat.');
        $data = $request->validated();
        $paths = [];
        try {
            foreach (['akta_kematian', 'kartu_bpjs', 'buku_rekening'] as $key) {
                $paths[$key] = $request->file($key)->store('dokumen/klaim', 'local');
            }
            $pengajuan = DB::transaction(function () use ($peserta, $data, $paths) {
                $pengajuan = $peserta->pengajuanKlaim()->create(array_merge($data, $paths));
                $today = now()->toDateString();
                $last = NomorAntrian::whereDate('tanggal_antrian', $today)->lockForUpdate()->max('nomor_urut') ?? 0;
                $pengajuan->nomorAntrian()->create(['nomor_urut' => $last + 1, 'tanggal_antrian' => $today, 'jenis_pelayanan' => 'Klaim Jaminan Kematian', 'nomor_loket' => 'Loket 1', 'waktu_pengambilan' => now()]);

                return $pengajuan->load('nomorAntrian');
            });

            Inertia::flash('toast', ['type' => 'success', 'message' => 'Pengajuan berhasil diterima.']);
            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            foreach ($paths as $path) {
                Storage::disk('local')->delete($path);
            }
            throw $e;
        }
    }

    public function pdf(PengajuanKlaim $pengajuan)
    {
        $this->authorizeAccess($pengajuan);

        return Pdf::loadView('pengajuan-klaim.pdf', ['pengajuan' => $pengajuan->load(['peserta', 'nomorAntrian'])])->download('nomor-antrean-'.$pengajuan->nomorAntrian->nomor_urut.'.pdf');
    }

    public function document(PengajuanKlaim $pengajuan, string $document)
    {
        $this->authorizeAccess($pengajuan);
        abort_unless(in_array($document, ['akta_kematian', 'kartu_bpjs', 'buku_rekening'], true), 404);

        return response()->file(Storage::disk('local')->path($pengajuan->{$document}));
    }

    private function authorizeAccess(PengajuanKlaim $pengajuan): void
    {
        abort_unless(auth()->user()->isAdmin() || $pengajuan->peserta->user_id === auth()->id(), 403);
    }
}
