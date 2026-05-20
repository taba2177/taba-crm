<?php

namespace Taba\Crm\Filament\Client\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Taba\Crm\Models\Page;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static bool $shouldRegisterNavigation = true;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 70;

    public static function getNavigationGroup(): ?string
    {
        return __('إدارة الموقع');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationLabel(): string
    {
        return __('الصفحات');
    }

    public static function getModelLabel(): string
    {
        return __('صفحة');
    }

    public static function getPluralModelLabel(): string
    {
        return __('الصفحات');
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'pages';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make(__('المحتوى'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('العنوان'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label(__('الرابط'))
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\RichEditor::make('content')
                            ->label(__('المحتوى'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('العنوان'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('الرابط'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('آخر تحديث'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('title')
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Taba\Crm\Filament\Client\Resources\PageResource\Pages\ListPages::route('/'),
            'edit' => \Taba\Crm\Filament\Client\Resources\PageResource\Pages\EditPage::route('/{record}/edit'),
            'view' => \Taba\Crm\Filament\Client\Resources\PageResource\Pages\ViewPage::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
