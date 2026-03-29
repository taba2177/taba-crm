<div class="space-y-4 p-4">
    <div>
        <span class="font-semibold text-gray-500">{{ __('الاسم') }}:</span>
        <span>{{ $record->name }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-500">{{ __('البريد الإلكتروني') }}:</span>
        <span>{{ $record->email }}</span>
    </div>
    @if($record->phone)
    <div>
        <span class="font-semibold text-gray-500">{{ __('الهاتف') }}:</span>
        <span>{{ $record->phone }}</span>
    </div>
    @endif
    @if($record->service)
    <div>
        <span class="font-semibold text-gray-500">{{ __('الخدمة') }}:</span>
        <span>{{ $record->service }}</span>
    </div>
    @endif
    <div>
        <span class="font-semibold text-gray-500">{{ __('التاريخ') }}:</span>
        <span>{{ $record->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div>
        <span class="font-semibold text-gray-500">{{ __('الرسالة') }}:</span>
        <p class="mt-1 whitespace-pre-wrap text-gray-800 dark:text-gray-200">{{ $record->message }}</p>
    </div>
</div>
