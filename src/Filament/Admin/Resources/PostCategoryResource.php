<?php

namespace Taba\Crm\Filament\Admin\Resources;

use Taba\Crm\Filament\Admin\Resources\PostCategoryResource\Pages;
use Taba\Crm\Filament\Admin\Resources\PostCategoryResource\RelationManagers;
use Taba\Crm\Models\PostCategory;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use IbrahimBougaoua\RadioButtonImage\RadioButtonImage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Illuminate\Database\Eloquent\Model;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Taba\Crm\Filament\Clusters\Posts;
use Alkoumi\FilamentImageRadioButton\Forms\Components\ImageRadioGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Taba\Crm\Models\Post;
use Taba\Crm\Services\GeminiTranslationService;
use Taba\Crm\Components\Registry\ComponentRegistry;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;
    // protected static ?string $cluster = Posts::class;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

        protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('Post Category'); // Translate your desired label
    }
    public static function getHeading(): string
    {
        return __('Post Category');
    }
    public static function getSubheading(): ?string
    {
        return __('Post Category');
    }
        public static function getModelLabel(): string
    {
        return __('Post Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Post Category');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Checkbox::make('HEAVY_SECTION')
                ->label('Heavy Section')
                ->translateLabel()
                ->visible(fn () => auth()->user()->hasRole('super_admin'))
                ->afterStateHydrated(function (Set $set, ?PostCategory $record) {
                    if (!$record) {
                        return;
                    }
                    $isHeavy = $record->posts()->count() > 4;
                    $set('HEAVY_SECTION', $isHeavy);
                }),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->translateLabel()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->translateLabel()
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Select a parent category to create a subcategory'),

                Forms\Components\TextInput::make('name')
                ->translateLabel()
                ->suffixAction(
                                        Actions\Action::make('translateTitle')
                                            ->icon('heroicon-o-language')
                                            ->iconSize('sm')
                                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                                            ->tooltip('Auto-translate title')
                                            ->action(function (PostCategory $record, Set $set, Get $get) {
                                                try {
                                                    $currentLocale = app()->getLocale();
                                                    $oppositeLocale = $currentLocale === 'en' ? 'ar' : 'en';
                                                    $currentTitle = trim($get('name') ?? '');

                                                    if (empty($currentTitle)) {
                                                        $currentTitle = $record->getTranslation('name', $currentLocale, false);
                                                        $set('name', $currentTitle);
                                                    }

                                                    $translator = app(GeminiTranslationService::class);
                                                    $translated = $translator->translate(
                                                        $currentTitle,
                                                        $currentLocale,
                                                        $oppositeLocale
                                                    );

                                                    if ($translated) {
                                                        $record->setTranslation('name', $oppositeLocale, $translated);
                                                        $set('name', $translated);
                                                        $record->save();
                                                    }
                                                } catch (\Exception $e) {
                                                    Notification::make()
                                                        ->title('Error')
                                                        ->body($e->getMessage())
                                                        ->danger()
                                                        ->send();
                                                    report($e);
                                                }
                                            })
                                    ),
                Forms\Components\Toggle::make('register_in_header')
                    ->translateLabel()
                    ->required(),
                Forms\Components\TextInput::make('order')->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('description')
                    //add more rows to the textinput
                    // ->rows(5)
                      ->suffixAction(
                                Actions\Action::make('translateTitle')
                                    ->icon('heroicon-o-language')
                                    ->iconSize('sm')
                                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                                    ->tooltip('Auto-translate title')
                                    ->action(function (PostCategory $record, Set $set, Get $get) {
                                        try {
                                            $currentLocale = app()->getLocale();
                                            $oppositeLocale = $currentLocale === 'en' ? 'ar' : 'en';
                                            $currentTitle = trim($get('description') ?? '');

                                            if (empty($currentTitle)) {
                                                $currentTitle = $record->getTranslation('description', $currentLocale, false);
                                                $set('description', $currentTitle);
                                            }

                                            $translator = app(GeminiTranslationService::class);
                                            $translated = $translator->translate(
                                                $currentTitle,
                                                $currentLocale,
                                                $oppositeLocale
                                            );

                                            if ($translated) {
                                                $record->setTranslation('description', $oppositeLocale, $translated);
                                                $set('description', $translated);
                                                $record->save();
                                            }
                                        } catch (\Exception $e) {
                                            Notification::make()
                                                ->title('Error')
                                                ->body($e->getMessage())
                                                ->danger()
                                                ->send();
                                            report($e);
                                        }
                                    })
                                )
                    ->translateLabel(),
                // CuratorPicker::make('image_id')->label('Featured Image')->translateLabel(),

                Forms\Components\TextInput::make('subtitle')
                  ->suffixAction(
                                        Actions\Action::make('translateTitle')
                                            ->icon('heroicon-o-language')
                                            ->iconSize('sm')
                                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                                            ->tooltip('Auto-translate title')
                                            ->action(function (PostCategory $record, Set $set, Get $get) {
                                                try {
                                                    $currentLocale = app()->getLocale();
                                                    $oppositeLocale = $currentLocale === 'en' ? 'ar' : 'en';
                                                    $currentTitle = trim($get('subtitle') ?? '');

                                                    if (empty($currentTitle)) {
                                                        $currentTitle = $record->getTranslation('subtitle', $currentLocale, false);
                                                        $set('subtitle', $currentTitle);
                                                    }

                                                    $translator = app(GeminiTranslationService::class);
                                                    $translated = $translator->translate(
                                                        $currentTitle,
                                                        $currentLocale,
                                                        $oppositeLocale
                                                    );

                                                    if ($translated) {
                                                        $record->setTranslation('subtitle', $oppositeLocale, $translated);
                                                        $set('subtitle', $translated);
                                                        $record->save();
                                                    }
                                                } catch (\Exception $e) {
                                                    Notification::make()
                                                        ->title('Error')
                                                        ->body($e->getMessage())
                                                        ->danger()
                                                        ->send();
                                                    report($e);
                                                }
                                            })
                                    )
                    ->translateLabel(),

                \Filament\Schemas\Components\Section::make()
                    ->columnSpanFull()
                    ->schema([
                        // Forms\Components\Select::make('section_component')
                        //     ->label('Select Section')
                        //     ->options(self::getHomepageComponentOptions())
                        //     ->reactive(),
                    // RadioDeck::make('section_component')
                    //         ->label('Select a Layout Style')
                    //         ->translateLabel()
                    //         ->options(self::getHomepageComponentOptions()) // Populates labels from the Enum
                    //         // ->images(CategoryLayout::getImageUrls()) // Populates images from the Enum
                    //         ->descriptions([
                    //             'default' => 'A standard, single-column list of posts.',
                    //             'grid' => 'A responsive grid view for post cards.',
                    //             'featured_post' => 'Highlights the latest post in a large block.',
                    //         ])
                    //         ->columns(3), // Display in 3 columns
                            // ->default(CategoryLayout::Default), // Set default for new records
                 RadioDeck::make('section_component')
                    ->label(__('اختر تصميم القسم'))
                    ->options(ComponentRegistry::frontendSections())
                    ->iconSize(IconSize::Large)
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->color('primary')
                    ->default(null)
                    ->nullable()
                    ->optionsGap('gap-5')
                    ->extraCardsAttributes([
                        'class' => 'mb-5'
                    ])
                    ->live(debounce: '500ms')
                    ->icons(ComponentRegistry::frontendSectionIcons())
                    ->columns(4)
                    ->dehydrateStateUsing(fn ($state) => $state === 'none' ? null : $state)
                    ->afterStateHydrated(function ($component, $state) {
                        if ($state === null || $state === '') {
                            $component->state('none');
                        }
                    }),
                    ]),

            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
        return ['none' => __('بدون تصميم')] + ComponentRegistry::frontendSections();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')->translateLabel(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\ToggleColumn::make('HEAVY_SECTION')
                    ->label('Heavy Section')
                    ->translateLabel()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),

                //section_component
                Tables\Columns\TextColumn::make('section_component')->translateLabel(),
                // ->formatStateUsing(fn(string $state): string => Str::limit(json_encode($state), 50))
                // ->tooltip(fn(string $state): string => json_encode($state)),
                // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('slug')->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('posts_count')->counts('posts')->translateLabel()->label('Number of Posts'),

                Tables\Columns\IconColumn::make('register_in_header')->translateLabel()
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')->translateLabel()
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\ActionGroup::make([
                    ListPreviewAction::make(),
                    Actions\EditAction::make(),
                    Actions\DeleteAction::make(),
                ]),
            ])

            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->translateLabel(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }
}
