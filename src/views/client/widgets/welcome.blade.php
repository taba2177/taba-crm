<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            {{-- Greeting --}}
            <div class="flex items-center gap-3">
                <x-filament::icon icon="heroicon-o-hand-raised" class="h-8 w-8 text-primary-500" />
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('مرحباً بك') }}{{ $this->getBusinessName() ? ' في ' . $this->getBusinessName() : '' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('لوحة تحكم الموقع') }}</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                {{-- Sections --}}
                <div class="bg-primary-50 dark:bg-primary-950 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $this->getSectionsCount() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('الأقسام') }}</div>
                </div>

                {{-- Posts --}}
                <div class="bg-success-50 dark:bg-success-950 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-success-600 dark:text-success-400">{{ $this->getPostsCount() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('المحتوى') }}</div>
                </div>

                {{-- Messages --}}
                <div class="bg-warning-50 dark:bg-warning-950 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-warning-600 dark:text-warning-400">{{ $this->getMessagesCount() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('الرسائل') }}</div>
                </div>

                {{-- Unread --}}
                <div class="bg-danger-50 dark:bg-danger-950 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-danger-600 dark:text-danger-400">{{ $this->getUnreadMessagesCount() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('غير مقروءة') }}</div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
