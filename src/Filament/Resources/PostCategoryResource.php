<?php

namespace Taba\Crm\Filament\Resources;

use Taba\Crm\Filament\Resources\PostCategoryResource\Pages;
use Taba\Crm\Filament\Resources\PostCategoryResource\RelationManagers;
use Taba\Crm\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use IbrahimBougaoua\RadioButtonImage\RadioButtonImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Illuminate\Database\Eloquent\Model;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Taba\Crm\Filament\Clusters\Posts;
use Alkoumi\FilamentImageRadioButton\Forms\Components\ImageRadioGroup;
use Filament\Support\Enums\IconSize;

class PostCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = PostCategory::class;
    // protected static ?string $cluster = Posts::class;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')->translateLabel(),
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
                    ->translateLabel(),
                // CuratorPicker::make('image_id')->label('Featured Image')->translateLabel(),

                Forms\Components\TextInput::make('subtitle')
                    ->translateLabel(),

                Forms\Components\Section::make()
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
                    ->label('Select a Layout Style')
                    ->options(self::getHomepageComponentOptions())
                    ->iconSize(IconSize::Large)
                    ->direction('column')
                    ->color('primary')
                    ->iconSizes([ // Customize the values for each icon size
                        'sm' => 'h-full w-full',
                        'md' => 'h-full w-full',
                        'lg' => 'h-full w-full',
                    ])->gap('gap-5')
                    // ->padding('px-4 px-6')
                    ->extraCardsAttributes([
                        'class' => 'mb-5'
                    ])
                    ->live(debounce: '500ms')
                    ->icons(function (): array {
                        // Get the option keys ('hero-section', 'testimonials', etc.)
                        $optionKeys = array_keys(self::getHomepageComponentOptions());
                        // Map each key to its corresponding image path
                        return collect($optionKeys)
                            ->mapWithKeys(fn ($key) => [
                                $key => asset("images/homepage/{$key}.png")
                            ])
                            ->all();
                    })
                    ->columns(3),
                    ]),

            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
        $componentPath = resource_path('views/components/homepage');
        $files = File::files($componentPath);
        $options = [];
        foreach ($files as $file) {
            $name = Str::before($file->getFilename(), '.blade.php');
            $options[$name] = Str::title(str_replace('-', ' ', $name));
        }
        return $options;
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
                //section_component
                Tables\Columns\TextColumn::make('section_component')
                    ->translateLabel(),
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
                Tables\Actions\ActionGroup::make([
                    ListPreviewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
