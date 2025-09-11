<?php

namespace Taba\Crm\Filament\Resources;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Taba\Crm\Filament\Resources\PostCategoryResource\Pages;
use Taba\Crm\Filament\Resources\PostCategoryResource\RelationManagers;
use Taba\Crm\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Taba\Crm\Filament\Clusters\Posts;
use Filament\Support\Enums\IconSize;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section as FormSection;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Cache;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PostCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = PostCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = null;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }

    public static function getNavigationLabel(): string
    {
        return __('Post Categories');
    }

    public static function getModelLabel(): string
    {
        return __('Post Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Post Categories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('CategoryTabs')
                    ->tabs([
                        Tabs\Tab::make(__('Basic Information'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if (request()->isMethod('POST')) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),

                                        Forms\Components\TextInput::make('slug')
                                            ->label(__('Slug'))
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->hint(__('Will be auto-generated if left empty')),
                                    ]),

                                Textarea::make('description')
                                    ->label(__('Description'))
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('subtitle')
                                    ->label(__('Subtitle'))
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('order')
                                            ->label(__('Order'))
                                            ->numeric()
                                            ->default(0)
                                            ->required(),

                                        Forms\Components\Toggle::make('register_in_header')
                                            ->label(__('Show in Header'))
                                            ->required()
                                            ->inline(false),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Content & Layout'))
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                FormSection::make(__('Layout Options'))
                                    ->description(__('Choose how your category posts will be displayed'))
                                    ->schema([
                                        RadioDeck::make('section_component')
                                            ->label(__('Layout Style'))
                                            ->required()
                                            ->options(self::getHomepageComponentOptions())
                                            ->icons(function (): array {
                                                $optionKeys = array_keys(self::getHomepageComponentOptions());
                                                return collect($optionKeys)
                                                    ->mapWithKeys(fn ($key) => [
                                                        $key => asset("images/homepage/{$key}.png")
                                                    ])
                                                    ->all();
                                            })
                                            ->iconSize(IconSize::Large)
                                            ->direction('column')
                                            ->color('primary')
                                            ->iconSizes([
                                                'sm' => 'h-full w-full',
                                                'md' => 'h-full w-full',
                                                'lg' => 'h-full w-full',
                                            ])
                                            ->gap('gap-5')
                                            ->extraCardsAttributes([
                                                'class' => 'mb-5'
                                            ])
                                            ->columns(3),
                                    ]),

                                FormSection::make(__('Content'))
                                    ->schema([
                                        TinyEditor::make('content')
                                            ->label(__('Category Content'))
                                            ->columnSpanFull()
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('posts/content')
                                            ->profile('simple')
                                            ->setConvertUrls(false),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Media & SEO'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FormSection::make(__('Featured Image'))
                                    ->schema([
                                        CuratorPicker::make('image')
                                            // ->label('')
                                            // ->multiple(false)
                                            // ->reorderable(false)
                                            // ->image()
                                            // ->imageEditor()
                                            // ->openable()
                                            // ->downloadable()
                                            ->columnSpanFull(),
                                    ]),

                                FormSection::make(__('SEO Settings'))
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label(__('Meta Title'))
                                            ->maxLength(60)
                                            ->hint(fn ($state) => strlen($state) . '/60')
                                            ->reactive(),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label(__('Meta Description'))
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->hint(fn ($state) => strlen($state) . '/160')
                                            ->reactive(),

                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label(__('Meta Keywords'))
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('Advanced'))
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Fieldset::make(__('Advanced Settings'))
                                    ->schema([
                                        Forms\Components\ColorPicker::make('color')
                                            ->label(__('Category Color')),

                                        // Forms\Components\Select::make('parent_id')
                                        //     ->label(__('Parent Category'))
                                        //     ->options(PostCategory::where('id', '!=', fn($get) => $get('id'))->pluck('name', 'id'))
                                        //     ->searchable()
                                        //     ->preload(),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label(__('Publish Date'))
                                            ->default(now()),

                                        ToggleButtons::make('status')
                                            ->label(__('Status'))
                                            ->options([
                                                'draft' => __('Draft'),
                                                'review' => __('Review'),
                                                'published' => __('Published'),
                                                'archived' => __('Archived'),
                                            ])
                                            ->colors([
                                                'draft' => 'gray',
                                                'review' => 'warning',
                                                'published' => 'success',
                                                'archived' => 'danger',
                                            ])
                                            ->icons([
                                                'draft' => 'heroicon-o-pencil',
                                                'review' => 'heroicon-o-eye',
                                                'published' => 'heroicon-o-check-circle',
                                                'archived' => 'heroicon-o-archive-box',
                                            ])
                                            ->inline()
                                            ->default('draft'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
        return Cache::remember('homepage_component_options', 3600, function () {
            $componentPath = resource_path('views/components/homepage');
            if (!File::exists($componentPath)) {
                return ['default' => 'Default Layout'];
            }

            $files = File::files($componentPath);
            $options = [];

            foreach ($files as $file) {
                $name = Str::before($file->getFilename(), '.blade.php');
                $options[$name] = Str::title(str_replace(['-', '_'], ' ', $name));
            }

            return $options;
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('image')
                    ->label(__('Image'))
                    // ->collection('post_categories')
                    // ->conversion('thumb')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-category.png'))
                    ->size(40),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (PostCategory $record) => $record->description ? Str::limit($record->description, 50) : '')
                    ->wrap(),

                TextColumn::make('posts_count')
                    ->label(__('Posts'))
                    ->counts('posts')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'gray',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('section_component')
                    ->label(__('Layout'))
                    ->formatStateUsing(fn ($state) => Str::title(str_replace(['-', '_'], ' ', $state)))
                    ->badge()
                    ->color('info'),

                IconColumn::make('register_in_header')
                    ->label(__('In Header'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->colors([
                        'draft' => 'gray',
                        'review' => 'warning',
                        'published' => 'success',
                        'archived' => 'danger',
                    ])
                    ->formatStateUsing(fn ($state) => __(ucfirst($state)))
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->tooltip(fn (PostCategory $record) => $record->updated_at->diffForHumans()),
            ])
            ->filters([
                SelectFilter::make('section_component')
                    ->label(__('Layout Style'))
                    ->options(self::getHomepageComponentOptions())
                    ->searchable(),

                TernaryFilter::make('register_in_header')
                    ->label(__('Show in Header')),

                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'draft' => __('Draft'),
                        'review' => __('Review'),
                        'published' => __('Published'),
                        'archived' => __('Archived'),
                    ]),

                TernaryFilter::make('has_posts')
                    ->label(__('Has Posts'))
                    ->queries(
                        true: fn (Builder $query) => $query->has('posts'),
                        false: fn (Builder $query) => $query->doesntHave('posts'),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ListPreviewAction::make()
                        ->icon('heroicon-o-eye')
                        ->label(__('Preview')),

                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->label(__('Edit')),

                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->label(__('Delete')),

                    Tables\Actions\RestoreAction::make()
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->label(__('Restore')),

                    Tables\Actions\Action::make('viewOnline')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->label(__('View Online'))
                        ->url(fn (PostCategory $record) => route('blog.category', ['category' => $record->slug]))
                        ->openUrlInNewTab()
                        ->visible(fn (PostCategory $record) => $record->status === 'published'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip(__('Actions')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('Delete Selected')),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label(__('Restore Selected')),

                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label(__('Update Status'))
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label(__('Status'))
                                ->options([
                                    'draft' => __('Draft'),
                                    'review' => __('Review'),
                                    'published' => __('Published'),
                                    'archived' => __('Archived'),
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->status = $data['status'];
                                $record->save();
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label(__('New Category')),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->groups([
                Group::make('section_component')
                    ->label(__('Layout Style'))
                    ->collapsible(),

                Group::make('status')
                    ->label(__('Status'))
                    ->collapsible(),

                Group::make('register_in_header')
                    ->label(__('Header Visibility'))
                    ->collapsible(),
            ])
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession();
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'description'];
    }
}