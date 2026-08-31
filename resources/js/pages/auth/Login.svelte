<script module lang="ts">
    export const layout = {
        title: 'Masuk ke akun',
        description: 'Masukkan NIK dan password untuk masuk',
    };
</script>

<script lang="ts">
    import { Form } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import PasswordInput from '@/components/PasswordInput.svelte';
    import TextLink from '@/components/TextLink.svelte';
    import { Button } from '@/components/ui/button';
    import { Checkbox } from '@/components/ui/checkbox';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { register } from '@/routes';
    import { store } from '@/routes/login';
    import PasskeyVerify from '@/components/PasskeyVerify.svelte';

    let {
        status = '',
        canResetPassword,
    }: {
        status?: string;
        canResetPassword: boolean;
    } = $props();
</script>

<AppHead title="Log in" />

{#if status}
    <div class="mb-4 text-center text-sm font-medium text-green-600">
        {status}
    </div>
{/if}

<PasskeyVerify />

<Form
    {...store.form()}
    resetOnSuccess={['password']}
    class="flex flex-col gap-6"
>
    {#snippet children({ errors, processing })}
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="username">NIK</Label>
                <Input
                    id="username"
                    type="text"
                    name="username"
                    required
                    inputmode="numeric"
                    autocomplete="username"
                    placeholder="16 digit NIK"
                />
                <InputError message={errors.username} />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                <Label for="password">Kata sandi</Label>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                />
                <InputError message={errors.password} />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" name="remember" />
                    <span>Ingat saya di perangkat ini</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                disabled={processing}
                data-test="login-button"
            >
                {#if processing}<Spinner />{/if}
                Masuk
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
             Belum punya akun?
             <TextLink href={register()}>Daftar</TextLink>
        </div>
    {/snippet}
</Form>
