<script module lang="ts">
    export const layout = {
        title: 'Daftar sebagai peserta',
        description: 'Daftarkan data kepesertaan Anda',
    };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import PasswordInput from '@/components/PasswordInput.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { login } from '@/routes';
    import { store } from '@/routes/register';

    let { passwordRules }: { passwordRules: string } = $props();

    let nikValue = $state('');
</script>

<AppHead title="Daftar" />

<Form
    {...store.form()}
    forceFormData
    resetOnSuccess={['password', 'password_confirmation']}
    class="flex flex-col gap-6"
>
    {#snippet children({ errors, processing })}
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">Nama lengkap</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autocomplete="name"
                    name="name"
                    placeholder="Nama lengkap"
                />
                <InputError message={errors.name} />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="nik">NIK</Label>
                    {#if nikValue.length > 0}
                        <span class="text-xs {nikValue.length === 16 ? 'text-emerald-600 font-medium' : 'text-amber-600 font-medium'}">
                            {nikValue.length}/16 digit
                        </span>
                    {/if}
                </div>
                <Input
                    id="nik"
                    type="text"
                    required
                    inputmode="numeric"
                    autocomplete="username"
                    name="nik"
                    placeholder="16 digit NIK"
                    maxlength={16}
                    bind:value={nikValue}
                    oninput={(e) => {
                        nikValue = e.currentTarget.value.replace(/\D/g, '').slice(0, 16);
                        e.currentTarget.value = nikValue;
                    }}
                />
                <p class="text-xs text-muted-foreground">Masukkan 16 digit NIK sesuai KTP tanpa spasi.</p>
                <InputError message={errors.nik} />
            </div>

            <div class="grid gap-2">
                <Label for="no_hp">Nomor HP</Label>
                <Input
                    id="no_hp"
                    type="tel"
                    required
                    name="no_hp"
                    autocomplete="tel"
                    inputmode="tel"
                    placeholder="08xxxxxxxxxx"
                    oninput={(e) => {
                        e.currentTarget.value = e.currentTarget.value.replace(/[^0-9+]/g, '').slice(0, 16);
                    }}
                />
                <p class="text-xs text-muted-foreground">Gunakan nomor HP yang aktif agar petugas dapat menghubungi Anda.</p>
                <InputError message={errors.no_hp} />
            </div>

            <div class="grid gap-2">
                <Label for="foto_ktp">Foto KTP</Label>
                <Input id="foto_ktp" type="file" required name="foto_ktp" accept="image/jpeg,image/png,application/pdf" />
                <p class="text-xs text-muted-foreground">JPG, PNG, atau PDF · maksimal 5 MB.</p>
                <InputError message={errors.foto_ktp} />
            </div>

            <div class="grid gap-2">
                <Label for="password">Kata sandi</Label>
                <PasswordInput
                    id="password"
                    required
                    autocomplete="new-password"
                    name="password"
                    placeholder="Kata sandi"
                    passwordrules={passwordRules}
                />
                <InputError message={errors.password} />
                <p class="text-xs text-muted-foreground">Gunakan kata sandi yang panjang dan mudah Anda ingat.</p>
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Ulangi kata sandi</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Ulangi kata sandi"
                    passwordrules={passwordRules}
                />
                <InputError message={errors.password_confirmation} />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                disabled={processing}
                data-test="register-user-button"
            >
                {#if processing}<Spinner />{/if}
                Daftar
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Sudah punya akun?
            <TextLink href={login()} class="underline underline-offset-4">
                Masuk
            </TextLink>
        </div>
    {/snippet}
</Form>
