<?php

namespace Taba\Crm\Filament\Client\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Taba\Crm\Models\CrmSetting;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 90;
    protected string $view = 'crm::client.site-settings';

    public static function getNavigationGroup(): ?string
    {
        return __('إدارة الموقع');
    }

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('إعدادات الموقع');
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'site-settings';
    }

    public function getTitle(): string
    {
        return __('إعدادات الموقع');
    }

    public function mount(): void
    {
        $keys = [
            'business_name', 'phone', 'email', 'address',
            'facebook', 'twitter', 'instagram', 'linkedin', 'youtube',
            'logo',
        ];

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = CrmSetting::get($key);
        }

        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make(__('معلومات النشاط'))
                    ->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->label(__('اسم النشاط'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('الهاتف'))
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('email')
                            ->label(__('البريد الإلكتروني'))
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label(__('العنوان'))
                            ->rows(2)
                            ->maxLength(500),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make(__('التواصل الاجتماعي'))
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->label(__('فيسبوك'))
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('twitter')
                            ->label(__('تويتر'))
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('instagram')
                            ->label(__('انستغرام'))
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('linkedin')
                            ->label(__('لينكد إن'))
                            ->url()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('youtube')
                            ->label(__('يوتيوب'))
                            ->url()
                            ->maxLength(500),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make(__('الشعار'))
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label(__('شعار الموقع'))
                            ->image()
                            ->directory('settings')
                            ->maxSize(2048),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            CrmSetting::set($key, $value, 'text', 'general');
        }

        Notification::make()
            ->title(__('تم حفظ الإعدادات بنجاح'))
            ->success()
            ->send();
    }
}
