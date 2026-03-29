<x-filament-panels::page>
    <div class="space-y-6" dir="rtl">
        {{-- Welcome Header --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('مرحباً') }}{{ $this->getBusinessName() ? '، ' . $this->getBusinessName() : '' }}
            </h2>
            <p class="mt-1 text-gray-500 dark:text-gray-400">{{ __('إليك نظرة عامة على موقعك') }}</p>
        </div>

        {{-- Stats Grid --}}
        @php $stats = $this->getStats(); @endphp
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Sections Card --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                        <x-heroicon-o-squares-2x2 class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('الأقسام النشطة') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sections'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Posts Card --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-success-50 dark:bg-success-900/20 rounded-lg">
                        <x-heroicon-o-document-text class="w-6 h-6 text-success-600 dark:text-success-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('المنشورات') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['posts'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Messages Card --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-warning-50 dark:bg-warning-900/20 rounded-lg">
                        <x-heroicon-o-envelope class="w-6 h-6 text-warning-600 dark:text-warning-400" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('الرسائل') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['messages'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Messages --}}
        @php $messages = $this->getRecentMessages(); @endphp
        @if($messages->isNotEmpty())
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('آخر الرسائل') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead class="text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                        <tr>
                            <th class="pb-3 font-medium">{{ __('الاسم') }}</th>
                            <th class="pb-3 font-medium">{{ __('البريد') }}</th>
                            <th class="pb-3 font-medium">{{ __('الرسالة') }}</th>
                            <th class="pb-3 font-medium">{{ __('التاريخ') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($messages as $message)
                        <tr>
                            <td class="py-3">{{ $message->name ?? '-' }}</td>
                            <td class="py-3">{{ $message->email ?? '-' }}</td>
                            <td class="py-3 max-w-xs truncate">{{ $message->message ?? '-' }}</td>
                            <td class="py-3 text-gray-500">{{ $message->created_at?->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>
