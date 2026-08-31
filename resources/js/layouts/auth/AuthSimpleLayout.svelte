<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';
    import { home } from '@/routes';

    let {
        title = '',
        description = '',
        children,
    }: {
        title?: string;
        description?: string;
        children?: Snippet;
    } = $props();
</script>

<div class="flex min-h-svh flex-col items-center justify-center bg-[#eef5f7] p-4 md:p-8">
    <div class="grid w-full max-w-5xl overflow-hidden rounded-2xl border border-border bg-white shadow-xl md:grid-cols-[0.85fr_1.15fr]">
        <aside class="hidden bg-primary p-10 text-primary-foreground md:flex md:flex-col md:justify-between">
            <div><p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Pelayanan publik</p><h2 class="mt-6 text-3xl font-semibold leading-tight">Pelayanan Klaim Jaminan Kematian</h2><p class="mt-4 text-sm leading-6 text-slate-200">Lengkapi data kepesertaan dan dapatkan nomor antrean sebelum datang ke kantor pelayanan.</p></div>
            <p class="text-xs text-slate-300">Kabupaten Bungo · 08.00–16.00 WIB</p>
        </aside>
        <div class="p-6 sm:p-10">
        <div class="flex flex-col gap-8">
            <div class="flex flex-col items-center gap-4">
                <Link
                    href={home()}
                    class="flex flex-col items-center gap-2 font-medium"
                >
                    <div class="mb-1 flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-lg font-bold text-primary-foreground">PK</div>
                    <span class="text-sm font-semibold text-primary">Pelayanan Klaim</span>
                </Link>
                <div class="space-y-2 text-center">
                    <h1 class="text-xl font-medium">{title}</h1>
                    <p class="text-center text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            {@render children?.()}
        </div>
        </div>
    </div>
</div>
