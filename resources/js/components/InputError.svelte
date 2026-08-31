<script lang="ts">
    let {
        message = '',
        class: className = '',
    }: {
        message?: string;
        class?: string;
    } = $props();
</script>

{#if message}
    <div class={className}>
        <p role="alert" class="text-sm text-red-700">{message}</p>
    </div>
{/if}
