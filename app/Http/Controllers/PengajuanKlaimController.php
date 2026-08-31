<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanKlaimRequest;
use App\Models\NomorAntrian;
use App\Models\PengajuanKlaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Services\ClaimPdf;

class PengajuanKlaimController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.pengajuan.index');
        }
        $peserta = $user->peserta()->with(['pengajuanKlaim.nomorAntrian'])->firstOrFail();

        return Inertia::render('Dashboard', ['peserta' => $peserta, 'pengajuan' => $peserta->pengajuanKlaim, 'bankGroups' => config('banks')]);
    }

    public function store(StorePengajuanKlaimRequest $request)
    {
        $peserta = $request->user()->peserta;
        abort_unless($peserta && ! $peserta->pengajuanKlaim()->exists(), 422, 'Pengajuan klaim sudah pernah dibuat.');
        $data = $request->validated();
        $bankChoice = $data['bank_choice'];
        $data['nama_bank'] = $bankChoice === '__other__' ? trim($data['nama_bank_lainnya']) : $bankChoice;
        unset($data['bank_choice'], $data['nama_bank_lainnya']);
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

    public function pdf(PengajuanKlaim $pengajuan, ClaimPdf $claimPdf)
    {
        $this->authorizeAccess($pengajuan);

        $content = $claimPdf->generate($pengajuan);
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="pengajuan-klaim-'.$pengajuan->id.'-antrean-'.$pengajuan->nomorAntrian->nomor_urut.'.pdf"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function document(PengajuanKlaim $pengajuan, string $document)
    {
        $this->authorizeAccess($pengajuan);
        if ($document === 'foto_ktp') {
            $path = $pengajuan->peserta->foto_ktp;
        } else {
            abort_unless(in_array($document, ['akta_kematian', 'kartu_bpjs', 'buku_rekening'], true), 404);
            $path = $pengajuan->{$document};
        }
        abort_unless(Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), [], [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function authorizeAccess(PengajuanKlaim $pengajuan): void
    {
        abort_unless(auth()->user()->isAdmin() || $pengajuan->peserta->user_id === auth()->id(), 403);
    }
}
