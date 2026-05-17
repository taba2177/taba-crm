<?php

namespace Taba\Crm\Filament\Admin\Resources\CrmSettingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\Page;
use Taba\Crm\Filament\Admin\Resources\CrmSettingResource;
use Taba\Crm\Models\CrmSetting;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;

class ManageCrmSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CrmSettingResource::class;

    protected string $view = 'crm::filament.pages.manage-crm-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema(CrmSettingResource::form($form)->getComponents())
            ->statePath('data');
    }

    protected function loadSettings(): void
    {
        $settings = CrmSetting::orderBy('group')->orderBy('order')->get();

        $formData = [];

        foreach ($settings as $setting) {
            if ($setting->is_translatable && is_array($setting->value)) {
                $formData[$setting->key . '_en'] = $setting->value['en'] ?? '';
                $formData[$setting->key . '_ar'] = $setting->value['ar'] ?? '';
            } else {
                $formData[$setting->key] = $setting->value;
            }
        }

        $this->form->fill($formData);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $groupedData = [];
        foreach ($data as $key => $value) {
            if (preg_match('/^(.+)_(en|ar)$/', $key, $matches)) {
                $baseKey = $matches[1];
                $locale = $matches[2];
                $groupedData[$baseKey][$locale] = $value;
            } else {
                $groupedData[$key] = $value;
            }
        }

        foreach ($groupedData as $key => $value) {
            $setting = CrmSetting::where('key', $key)->first();

            if ($setting) {
                if (is_array($value) && isset($value['en']) && isset($value['ar'])) {
                    $setting->value = $value;
                    $setting->is_translatable = true;
                } else {
                    $setting->value = $value;
                }
                $setting->save();
            } else {
                $isTranslatable = is_array($value) && isset($value['en']) && isset($value['ar']);

                $group = 'general';
                if (str_starts_with($key, 'crm_contact_')) {
                    $group = in_array($key, ['crm_contact_facebook', 'crm_contact_twitter', 'crm_contact_instagram', 'crm_contact_linkedin', 'crm_contact_youtube'])
                        ? 'social'
                        : 'contact';
                } elseif (str_starts_with($key, 'crm_business_')) {
                    $group = 'business';
                } elseif (str_starts_with($key, 'crm_seo_')) {
                    $group = 'seo';
                } elseif (str_starts_with($key, 'crm_gemini_') || str_starts_with($key, 'crm_unsplash_')) {
                    $group = 'api';
                } elseif (str_starts_with($key, 'crm_brand_')) {
                    $group = 'brand';
                } elseif (str_starts_with($key, 'crm_login_')) {
                    $group = 'login';
                } elseif (str_starts_with($key, 'crm_nav_')) {
                    $group = 'navigation';
                }

                CrmSetting::create([
                    'key' => $key,
                    'value' => $value,
                    'type' => is_array($value) ? 'json' : 'text',
                    'group' => $group,
                    'is_translatable' => $isTranslatable,
                ]);
            }
        }

        try {
            \Artisan::call('config:clear');
        } catch (\Exception $e) {
            // Ignore
        }

        Notification::make()
            ->title(__('Settings saved successfully'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label(__('Save Changes'))
                ->submit('save')
                ->color('primary')
                ->icon('heroicon-o-check-circle'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reset')
                ->label(__('Reset to Defaults'))
                ->action('resetSettings')
                ->color('danger')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading(__('Reset Settings'))
                ->modalDescription(__('This will reset all settings to their default values. Are you sure?')),
        ];
    }

    public function resetSettings(): void
    {
        \Artisan::call('db:seed', [
            '--class' => 'Taba\\Crm\\Database\\Seeders\\CrmSettingsSeeder',
        ]);

        $this->loadSettings();

        Notification::make()
            ->title(__('Settings reset to defaults'))
            ->success()
            ->send();
    }
}
