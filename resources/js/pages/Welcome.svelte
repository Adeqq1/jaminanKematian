<script lang="ts">
    import { Link, page } from '@inertiajs/svelte';
    import ArrowRight from '@lucide/svelte/icons/arrow-right';
    import CheckCircle2 from '@lucide/svelte/icons/check-circle-2';
    import ClipboardList from '@lucide/svelte/icons/clipboard-list';
    import FileCheck2 from '@lucide/svelte/icons/file-check-2';
    import IdCard from '@lucide/svelte/icons/id-card';
    import LogIn from '@lucide/svelte/icons/log-in';
    import ShieldCheck from '@lucide/svelte/icons/shield-check';
    import AppHead from '@/components/AppHead.svelte';
    import { dashboard, login, register } from '@/routes';

    const auth = $derived(page.props.auth);
    const documents = ['KTP peserta', 'Akta kematian', 'Kartu BPJS', 'Buku rekening ahli waris'];
    const steps = [
        { number: '01', title: 'Buat akun', text: 'Daftarkan diri menggunakan NIK dan data kepesertaan.' },
        { number: '02', title: 'Lengkapi pengajuan', text: 'Isi data ahli waris dan unggah dokumen pendukung.' },
        { number: '03', title: 'Dapatkan antrean', text: 'Sistem memberikan nomor antrean layanan secara otomatis.' },
        { number: '04', title: 'Datang ke kantor', text: 'Tunjukkan bukti antrean dan bawa dokumen asli.' },
    ];
</script>

<AppHead title="Pelayanan Klaim Jaminan Kematian" />

<div class="min-h-screen bg-[#f4f8fa] text-[#17252f]">
    <header class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 lg:px-10">
        <Link href="/" class="flex items-center gap-3" aria-label="Pelayanan Klaim Jaminan Kematian">
            <span class="flex size-10 items-center justify-center rounded-xl bg-primary text-sm font-bold text-primary-foreground">PK</span>
            <span class="max-w-44 text-sm font-semibold leading-tight text-primary sm:max-w-none">Pelayanan Klaim<br class="sm:hidden" /> Jaminan Kematian</span>
        </Link>
        <nav class="flex items-center gap-2 text-sm font-medium sm:gap-4">
            {#if auth.user}
                <Link href={dashboard()} class="rounded-lg px-3 py-2 text-primary hover:bg-white">Dashboard</Link>
            {:else}
                <Link href={login()} class="rounded-lg px-3 py-2 text-primary hover:bg-white">Masuk</Link>
                <Link href={register()} class="rounded-lg bg-primary px-4 py-2.5 text-primary-foreground shadow-sm hover:bg-[#0b2c47]">Daftar</Link>
            {/if}
        </nav>
    </header>

    <main>
        <section class="mx-auto grid max-w-7xl gap-10 px-5 pb-16 pt-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:px-10 lg:pb-24 lg:pt-20">
            <div>
                <p class="mb-5 inline-flex items-center gap-2 rounded-full bg-[#dceff0] px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.15em] text-[#126b68]"><ShieldCheck class="size-4" /> Layanan digital Kabupaten Bungo</p>
                <h1 class="max-w-2xl text-4xl font-semibold leading-[1.08] tracking-tight text-primary sm:text-6xl">Ajukan klaim dengan langkah yang lebih jelas.</h1>
                <p class="mt-6 max-w-xl text-base leading-7 text-[#536673] sm:text-lg">Lengkapi data secara online, siapkan dokumen, dan dapatkan nomor antrean sebelum datang ke kantor pelayanan.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <Link href={auth.user ? dashboard() : register()} class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-primary px-5 font-semibold text-primary-foreground shadow-lg shadow-primary/15 hover:bg-[#0b2c47]">{auth.user ? 'Buka pengajuan' : 'Daftar sebagai peserta'} <ArrowRight class="size-4" /></Link>
                    <Link href={auth.user ? dashboard() : login()} class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-border bg-white px-5 font-semibold text-primary hover:bg-secondary"><LogIn class="size-4" /> Masuk dengan NIK</Link>
                </div>
                <p class="mt-5 text-xs text-[#657680]">Data identitas dan dokumen Anda disimpan dengan akses terbatas.</p>
            </div>
            <div class="relative overflow-hidden rounded-3xl bg-primary p-7 text-white shadow-2xl shadow-primary/15 sm:p-10">
                <div class="absolute -right-16 -top-16 size-48 rounded-full border-[24px] border-[#2f6079] opacity-60"></div>
                <div class="relative"><p class="text-sm font-medium text-teal-200">Yang akan Anda dapatkan</p><p class="mt-3 text-3xl font-semibold leading-tight">Bukti pengajuan dan nomor antrean dalam satu tempat.</p><div class="mt-8 space-y-4">{#each ['Formulir tersimpan', 'Nomor antrean harian', 'Bukti PDF siap diunduh'] as item}<div class="flex items-center gap-3 text-sm text-slate-100"><CheckCircle2 class="size-5 text-teal-300" /> {item}</div>{/each}</div><div class="mt-10 rounded-2xl bg-white/10 p-4 text-sm leading-6 text-slate-200">Pelayanan Klaim Jaminan Kematian<br /><span class="font-semibold text-white">Senin–Jumat · 08.00–16.00 WIB</span></div></div>
            </div>
        </section>

        <section class="border-y border-[#e1eaee] bg-white"><div class="mx-auto grid max-w-7xl gap-8 px-5 py-12 lg:grid-cols-[0.8fr_1.2fr] lg:px-10"><div><p class="text-sm font-semibold text-[#16865c]">Sebelum memulai</p><h2 class="mt-2 text-2xl font-semibold text-primary">Siapkan dokumen pendukung</h2><p class="mt-3 text-sm leading-6 text-[#536673]">Pastikan dokumen terbaca jelas dalam format JPG, PNG, atau PDF dengan ukuran maksimal 5 MB per file.</p></div><div class="grid gap-3 sm:grid-cols-2">{#each documents as document, index}<div class="flex items-center gap-3 rounded-xl border border-border bg-[#f8fbfc] p-4"><span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-[#dceff0] text-sm font-bold text-[#126b68]">{index + 1}</span><span class="text-sm font-medium">{document}</span></div>{/each}</div></div></section>

        <section class="mx-auto max-w-7xl px-5 py-16 lg:px-10 lg:py-24"><div class="max-w-xl"><p class="text-sm font-semibold text-[#16865c]">Alur pelayanan</p><h2 class="mt-2 text-3xl font-semibold text-primary">Empat langkah sederhana</h2></div><div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">{#each steps as step}<div class="rounded-2xl border border-border bg-white p-5"><span class="text-sm font-bold text-[#16865c]">{step.number}</span><h3 class="mt-8 font-semibold text-primary">{step.title}</h3><p class="mt-2 text-sm leading-6 text-[#536673]">{step.text}</p></div>{/each}</div></section>

        <section class="bg-primary text-white"><div class="mx-auto grid max-w-7xl gap-8 px-5 py-12 sm:grid-cols-[1fr_auto] sm:items-center lg:px-10"><div><h2 class="text-2xl font-semibold">Perlu bantuan?</h2><p class="mt-2 text-sm leading-6 text-slate-200">Hubungi kantor pelayanan pada jam kerja jika membutuhkan bantuan akses atau koreksi data.</p></div><div class="rounded-xl bg-white/10 px-5 py-4 text-sm leading-6"><strong>+62 813-6184-563</strong><br />08.00–16.00 WIB</div></div></section>
    </main>
    <footer class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-8 text-xs text-[#657680] sm:flex-row sm:items-center sm:justify-between lg:px-10"><span>Pelayanan Klaim Jaminan Kematian · Kabupaten Bungo</span><span>Jl. Sultan Thaha No.111, Muara Bungo, Jambi 37211</span></footer>
</div>
