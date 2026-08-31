<script module lang="ts">
    import { dashboard } from '@/routes';
    export const layout = { breadcrumbs: [{ title: 'Pengajuan klaim', href: dashboard() }] };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import CheckCircle2 from '@lucide/svelte/icons/check-circle-2';
    import FileText from '@lucide/svelte/icons/file-text';
    import Info from '@lucide/svelte/icons/info';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';

    type Peserta = { nama: string; nik: string; no_hp: string };
    type Pengajuan = { id: number; nama_ahli_waris: string; nomor_antrian?: { nomor_urut: number; jenis_pelayanan: string; nomor_loket: string; waktu_pengambilan: string } };
    let { peserta, pengajuan }: { peserta: Peserta; pengajuan: Pengajuan | null } = $props();
    const documents = [['akta_kematian', 'Akta kematian'], ['kartu_bpjs', 'Kartu BPJS'], ['buku_rekening', 'Buku rekening']];
</script>

<AppHead title="Pengajuan klaim" />
<div class="mx-auto flex w-full max-w-5xl flex-col gap-6 p-4 sm:p-6 lg:p-8">
    <header><p class="text-sm font-semibold text-[#16865c]">Layanan peserta</p><h1 class="mt-1 text-3xl font-semibold tracking-tight text-primary">Pengajuan klaim</h1><p class="mt-2 text-sm text-muted-foreground">Lengkapi data untuk mendapatkan nomor antrean Klaim Jaminan Kematian.</p></header>
    {#if pengajuan?.nomor_antrian}
        <section class="overflow-hidden rounded-2xl border border-[#bfe3d3] bg-white shadow-sm" aria-labelledby="ticket-title">
            <div class="bg-[#eaf8f0] p-6 sm:p-8"><div class="flex items-start gap-3"><CheckCircle2 class="mt-0.5 size-6 shrink-0 text-[#16865c]" /><div><h2 id="ticket-title" class="text-lg font-semibold text-[#126b4b]">Pengajuan berhasil diterima</h2><p class="mt-1 text-sm text-[#39725b]">Simpan bukti ini dan tunjukkan kepada petugas saat datang.</p></div></div><div class="mt-8 grid gap-6 sm:grid-cols-[1fr_auto] sm:items-end"><div><p class="text-sm font-medium text-[#39725b]">Nomor antrean Anda</p><p class="mt-1 text-7xl font-bold tracking-tight text-primary">{pengajuan.nomor_antrian.nomor_urut}</p></div><div class="space-y-2 text-sm text-[#39725b] sm:text-right"><p><strong class="text-primary">{pengajuan.nomor_antrian.jenis_pelayanan}</strong></p><p>{pengajuan.nomor_antrian.nomor_loket}</p><p>{new Date(pengajuan.nomor_antrian.waktu_pengambilan).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })} WIB</p></div></div></div>
            <div class="grid gap-4 p-6 sm:grid-cols-2 sm:p-8"><div><p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Peserta</p><p class="mt-1 font-medium">{peserta.nama}</p><p class="text-sm text-muted-foreground">NIK {peserta.nik}</p></div><div><p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Ahli waris</p><p class="mt-1 font-medium">{pengajuan.nama_ahli_waris}</p></div><div class="flex items-center gap-2 text-sm text-muted-foreground sm:col-span-2"><Info class="size-4 text-[#1976b9]" /> Bawa dokumen asli saat datang ke kantor pelayanan.</div><a href={`/pengajuan-klaim/${pengajuan.id}/pdf`} class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground hover:bg-[#0b2c47]"><FileText class="size-4" /> Unduh bukti PDF</a></div>
        </section>
    {:else}
        <div class="rounded-xl border border-[#c8e2ed] bg-[#eff8fb] p-4 text-sm text-[#28566d]"><div class="flex gap-3"><Info class="mt-0.5 size-5 shrink-0" /><p><strong>Siapkan dokumen sebelum mengisi.</strong><br />Semua file harus JPG, PNG, atau PDF dengan ukuran maksimal 5 MB.</p></div></div>
        <Form action="/pengajuan-klaim" method="post" enctype="multipart/form-data" forceFormData class="grid gap-8 rounded-2xl border bg-white p-5 shadow-sm sm:p-8">
            {#snippet children({ errors, processing })}
                <section class="grid gap-4" aria-labelledby="participant-section"><div><h2 id="participant-section" class="text-lg font-semibold text-primary">Data peserta</h2><p class="mt-1 text-sm text-muted-foreground">Pastikan data berikut sudah benar.</p></div><div class="grid gap-4 sm:grid-cols-2"><div class="grid gap-2"><Label for="nama_peserta">Nama lengkap</Label><Input id="nama_peserta" value={peserta.nama} readonly class="bg-muted" /></div><div class="grid gap-2"><Label for="nik_peserta">NIK peserta</Label><Input id="nik_peserta" name="nik_peserta" value={peserta.nik} readonly class="bg-muted" /><InputError message={errors.nik_peserta} /></div><div class="grid gap-2 sm:col-span-2"><Label for="no_hp_peserta">Nomor HP peserta</Label><Input id="no_hp_peserta" name="no_hp_peserta" value={peserta.no_hp} type="tel" inputmode="tel" required /><InputError message={errors.no_hp_peserta} /></div></div></section>
                <section class="grid gap-4 border-t pt-7" aria-labelledby="heir-section"><div><h2 id="heir-section" class="text-lg font-semibold text-primary">Data ahli waris</h2><p class="mt-1 text-sm text-muted-foreground">Masukkan orang yang menerima manfaat klaim.</p></div><div class="grid gap-4 sm:grid-cols-2"><div class="grid gap-2"><Label for="nama_ahli_waris">Nama lengkap ahli waris</Label><Input id="nama_ahli_waris" name="nama_ahli_waris" required /><InputError message={errors.nama_ahli_waris} /></div><div class="grid gap-2"><Label for="no_hp_ahli_waris">Nomor HP ahli waris</Label><Input id="no_hp_ahli_waris" name="no_hp_ahli_waris" type="tel" inputmode="tel" required /><InputError message={errors.no_hp_ahli_waris} /></div><div class="grid gap-2 sm:col-span-2"><Label for="keterangan_ahli_waris">Keterangan ahli waris</Label><textarea id="keterangan_ahli_waris" name="keterangan_ahli_waris" required placeholder="Contoh: hubungan dengan peserta dan keterangan pendukung" class="min-h-24 rounded-lg border border-input bg-background p-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"></textarea><InputError message={errors.keterangan_ahli_waris} /></div></div></section>
                <section class="grid gap-4 border-t pt-7" aria-labelledby="bank-section"><div><h2 id="bank-section" class="text-lg font-semibold text-primary">Rekening ahli waris</h2><p class="mt-1 text-sm text-muted-foreground">Gunakan rekening atas nama ahli waris jika tersedia.</p></div><div class="grid gap-4 sm:grid-cols-2"><div class="grid gap-2"><Label for="nomor_rekening_ahli_waris">Nomor rekening</Label><Input id="nomor_rekening_ahli_waris" name="nomor_rekening_ahli_waris" inputmode="numeric" required /><InputError message={errors.nomor_rekening_ahli_waris} /></div><div class="grid gap-2"><Label for="buku_rekening">Buku rekening</Label><Input id="buku_rekening" name="buku_rekening" type="file" accept="image/jpeg,image/png,application/pdf" required /><p class="text-xs text-muted-foreground">JPG, PNG, atau PDF · maksimal 5 MB</p><InputError message={errors.buku_rekening} /></div></div></section>
                <section class="grid gap-4 border-t pt-7" aria-labelledby="documents-section"><div><h2 id="documents-section" class="text-lg font-semibold text-primary">Dokumen pendukung</h2><p class="mt-1 text-sm text-muted-foreground">Pastikan tulisan dan identitas pada dokumen terlihat jelas.</p></div><div class="grid gap-4 sm:grid-cols-2">{#each documents as [name, label]}<div class="grid gap-2"><Label for={name}>{label}</Label><Input id={name} name={name} type="file" accept="image/jpeg,image/png,application/pdf" required /><p class="text-xs text-muted-foreground">JPG, PNG, atau PDF · maksimal 5 MB</p><InputError message={errors[name]} /></div>{/each}</div></section>
                <div class="border-t pt-7"><label class="flex items-start gap-3 text-sm text-muted-foreground"><input type="checkbox" required class="mt-1 size-4 accent-primary" /><span>Saya memastikan data dan dokumen yang dikirim sudah benar.</span></label><Button type="submit" disabled={processing} class="mt-5 min-h-11 w-full sm:w-auto">{#if processing}<Spinner /> Mengunggah dokumen…{:else}Kirim pengajuan dan ambil nomor antrean{/if}</Button></div>
            {/snippet}
        </Form>
    {/if}
</div>
