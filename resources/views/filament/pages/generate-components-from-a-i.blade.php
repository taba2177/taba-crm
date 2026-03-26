<x-filament-panels::page>
    {{-- Loading overlay --}}
    <div wire:loading.flex wire:target="generate, saveComponents"
        class="absolute inset-0 z-50 flex items-center justify-center bg-white/50 backdrop-blur-sm dark:bg-gray-900/50">
        <x-filament::loading-indicator class="h-10 w-10" />
    </div>

    <form wire:submit.prevent="generate">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-sparkles">
                Generate Components
            </x-filament::button>
        </div>
    </form>

    {{-- Results Section --}}
    @if ($generatedComponents)
    <div class="mt-8 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Generated Components
            </h3>
            <x-filament::button wire:click="saveComponents" icon="heroicon-o-arrow-down-tray" color="success">
                Save All Components
            </x-filament::button>
        </div>

        <div class="mt-6 space-y-6">
            @foreach ($generatedComponents as $filename => $code)
            <div class="rounded-lg border border-gray-300 dark:border-gray-700">
                <div
                    class="bg-gray-50 px-4 py-2 font-mono text-sm font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    {{ $filename }}
                </div>
                <pre class="max-h-96 overflow-auto p-4"><code class="language-php">{{ $code }}</code></pre>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-filament-panels::page>
