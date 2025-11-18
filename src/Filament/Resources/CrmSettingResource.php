<?php

namespace Taba\Crm\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Taba\Crm\Filament\Resources\CrmSettingResource\Pages;
use Taba\Crm\Models\CrmSetting;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;

class CrmSettingResource extends Resource
{
    protected static ?string $model = CrmSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('CRM Settings');
    }

    public static function getModelLabel(): string
    {
        return __('CRM Setting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('CRM Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Contact Information')
                            ->label(__('Contact Information'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Contact Details')
                                    ->description(__('Manage your business contact information'))
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('crm_contact_phone')
                                                    ->label(__('Phone Number'))
                                                    ->tel()
                                                    ->placeholder('+966500000000')
                                                    ->helperText(__('Business contact phone number')),

                                                Forms\Components\TextInput::make('crm_contact_email')
                                                    ->label(__('Email Address'))
                                                    ->email()
                                                    ->placeholder('info@example.com')
                                                    ->helperText(__('Business contact email')),

                                                Forms\Components\TextInput::make('crm_contact_address_en')
                                                    ->label(__('Street Address (English)'))
                                                    ->placeholder('123 Street Name'),

                                                Forms\Components\TextInput::make('crm_contact_address_ar')
                                                    ->label(__('Street Address (Arabic)'))
                                                    ->placeholder('شارع مثال، حي مثال'),

                                                Forms\Components\TextInput::make('crm_contact_city_en')
                                                    ->label(__('City (English)'))
                                                    ->placeholder('Riyadh'),

                                                Forms\Components\TextInput::make('crm_contact_city_ar')
                                                    ->label(__('City (Arabic)'))
                                                    ->placeholder('الرياض'),

                                                Forms\Components\TextInput::make('crm_contact_postal_code')
                                                    ->label(__('Postal Code'))
                                                    ->placeholder('12345'),

                                                Forms\Components\TextInput::make('crm_contact_latitude')
                                                    ->label(__('Latitude'))
                                                    ->numeric()
                                                    ->placeholder('24.774265')
                                                    ->helperText(__('GPS latitude coordinate')),

                                                Forms\Components\TextInput::make('crm_contact_longitude')
                                                    ->label(__('Longitude'))
                                                    ->numeric()
                                                    ->placeholder('46.738586')
                                                    ->helperText(__('GPS longitude coordinate')),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Social Media')
                            ->label(__('Social Media'))
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Social Media Links')
                                    ->description(__('Add your social media profile URLs'))
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('crm_contact_facebook')
                                                    ->label(__('Facebook'))
                                                    ->url()
                                                    ->placeholder('https://facebook.com/yourpage')
                                                    ->prefixIcon('heroicon-o-link'),

                                                Forms\Components\TextInput::make('crm_contact_twitter')
                                                    ->label(__('Twitter/X'))
                                                    ->url()
                                                    ->placeholder('https://twitter.com/youraccount')
                                                    ->prefixIcon('heroicon-o-link'),

                                                Forms\Components\TextInput::make('crm_contact_instagram')
                                                    ->label(__('Instagram'))
                                                    ->url()
                                                    ->placeholder('https://instagram.com/youraccount')
                                                    ->prefixIcon('heroicon-o-link'),

                                                Forms\Components\TextInput::make('crm_contact_linkedin')
                                                    ->label(__('LinkedIn'))
                                                    ->url()
                                                    ->placeholder('https://linkedin.com/company/yourcompany')
                                                    ->prefixIcon('heroicon-o-link'),

                                                Forms\Components\TextInput::make('crm_contact_youtube')
                                                    ->label(__('YouTube'))
                                                    ->url()
                                                    ->placeholder('https://youtube.com/@yourchannel')
                                                    ->prefixIcon('heroicon-o-link'),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Business Info')
                            ->label(__('Business Info'))
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make('Business Details')
                                    ->description(__('Manage your business information'))
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('crm_business_name_en')
                                                    ->label(__('Business Name (English)'))
                                                    ->placeholder('Your Company Name')
                                                    ->required(),

                                                Forms\Components\TextInput::make('crm_business_name_ar')
                                                    ->label(__('Business Name (Arabic)'))
                                                    ->placeholder('اسم شركتك')
                                                    ->required(),

                                                Forms\Components\TextInput::make('crm_business_price_range')
                                                    ->label(__('Price Range'))
                                                    ->placeholder('SAR 500 - SAR 20000')
                                                    ->helperText(__('Display price range for services')),

                                                Forms\Components\TextInput::make('crm_business_opens')
                                                    ->label(__('Opening Time'))
                                                    ->placeholder('09:00')
                                                    ->helperText(__('24-hour format (e.g., 09:00)'))
                                                    ->required(),

                                                Forms\Components\TextInput::make('crm_business_closes')
                                                    ->label(__('Closing Time'))
                                                    ->placeholder('18:00')
                                                    ->helperText(__('24-hour format (e.g., 18:00)'))
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO Defaults')
                            ->label(__('SEO Defaults'))
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Default SEO Settings')
                                    ->description(__('Default meta tags used across the website'))
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                Forms\Components\TextInput::make('crm_seo_default_title_en')
                                                    ->label(__('Default Title (English)'))
                                                    ->placeholder('Your Business Name')
                                                    ->maxLength(60)
                                                    ->helperText(__('Max 60 characters for optimal SEO')),

                                                Forms\Components\TextInput::make('crm_seo_default_title_ar')
                                                    ->label(__('Default Title (Arabic)'))
                                                    ->placeholder('اسم عملك')
                                                    ->maxLength(60)
                                                    ->helperText(__('Max 60 characters for optimal SEO')),

                                                Forms\Components\Textarea::make('crm_seo_default_description_en')
                                                    ->label(__('Default Description (English)'))
                                                    ->placeholder('Professional services and solutions...')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->helperText(__('Max 160 characters for optimal SEO')),

                                                Forms\Components\Textarea::make('crm_seo_default_description_ar')
                                                    ->label(__('Default Description (Arabic)'))
                                                    ->placeholder('خدمات وحلول احترافية...')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->helperText(__('Max 160 characters for optimal SEO')),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('API Keys')
                            ->label(__('API Keys'))
                            ->icon('heroicon-o-key')
                            ->schema([
                                Section::make('API Configuration')
                                    ->description(__('Manage API keys for external services'))
                                    ->schema([
                                        Forms\Components\TextInput::make('crm_gemini_api_key')
                                            ->label(__('Gemini API Key'))
                                            ->placeholder('AIzaSy...')
                                            ->password()
                                            ->revealable()
                                            ->helperText(__('Google Gemini AI API key for AI-powered features'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Setting Name'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($record) => $record->label ?? Str::title(str_replace('_', ' ', $record->key))),

                Tables\Columns\TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->colors([
                        'primary' => 'contact',
                        'success' => 'business',
                        'warning' => 'seo',
                        'danger' => 'api',
                        'info' => 'social',
                    ])
                    ->formatStateUsing(fn ($state) => Str::title($state)),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('Value'))
                    ->limit(50)
                    ->formatStateUsing(function ($record) {
                        if (is_array($record->value)) {
                            $locale = app()->getLocale();
                            return $record->value[$locale] ?? $record->value['en'] ?? json_encode($record->value);
                        }
                        return $record->value;
                    }),

                Tables\Columns\IconColumn::make('is_translatable')
                    ->label(__('Translatable'))
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label(__('Group'))
                    ->options([
                        'contact' => __('Contact'),
                        'social' => __('Social Media'),
                        'business' => __('Business'),
                        'seo' => __('SEO'),
                        'api' => __('API Keys'),
                    ]),
            ])
            ->defaultSort('order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCrmSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
