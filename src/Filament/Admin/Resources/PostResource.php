<?php

namespace Taba\Crm\Filament\Admin\Resources;

use Illuminate\Database\Eloquent\Model;

use Taba\Crm\Filament\Admin\Resources\PostResource\Pages;
use Taba\Crm\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Taba\Crm\Services\GeminiTranslationService;
use Taba\Crm\Models\Tag;
use Filament\Forms\Components\Select;
use Taba\Crm\Models\MetadataFillter;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Filament\Schemas\Components\Wizard;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Pages\Page;
use Taba\Crm\Models\PostCategory;
// SubNavigationPosition
use Filament\Pages\Enums\SubNavigationPosition;
use Guava\IconPicker\Forms\Components\IconPicker;
use Taba\Crm\Filament\Clusters\Posts;


class PostResource extends Resource
{
    /**
     * The resource record title.
     */
    protected static ?string $recordTitleAttribute = 'title';
    /**
     * The resource model.
     */
    protected static ?string $model = Post::class;

    //cluster
    // protected static ?string $cluster = Posts::class;

    /**
     * The resource icon.
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    // sub navigation posistin
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * The resource navigation group.
     */
    protected static string|\UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }
    protected static ?string $navigationLabel = null;

    public static function getNavigationItems(): array
    {
        if (! static::canViewAny()) {
            return [];
        }

        $items = [];

        $items[] = NavigationItem::make(__('All Posts'))
            ->url(static::getUrl('index'))
            ->icon(static::getNavigationIcon())
            ->group(static::getNavigationGroup())
            ->sort(static::$navigationSort)
            ->badge(static::getNavigationBadge(), static::getNavigationBadgeColor())
            ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.*') && !request()->query('category'));

        $categories = PostCategory::orderBy('order')->get();

        foreach ($categories as $category) {
            $categoryId = $category->id;
            $items[] = NavigationItem::make($category->name)
                ->label(__($category->name))
                ->url(static::getUrl('index') . '?category=' . $categoryId)
                ->icon('heroicon-o-folder')
                ->group(static::getNavigationGroup())
                ->sort(static::$navigationSort)
                ->badge(Post::where('post_category_id', $categoryId)->count() ?: null)
                ->isActiveWhen(fn () => request()->query('category') == $categoryId);
        }

        return $items;
    }
    public static function getNavigationLabel(): string
    {
        return __('Posts'); // Translate your desired label
    }
    public static function getHeading(): string
    {
        return __('Posts');
    }
    public static function getSubheading(): ?string
    {
        return null;
    }

    public static function getModelLabel(): string
    {
        return __('Posts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Posts');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery();
    }

    /**
     * The resource navigation sort order.
     */
    protected static ?int $navigationSort = 1;

    /**
     * Get the navigation badge for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    /**
     * Get the form for the resource.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('Content'))
                    ->icon('heroicon-o-pencil')
                    ->schema([
                            \Filament\Schemas\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('order')->translateLabel()
                                     ->required()
                                     ->numeric()
                                     ->default(0),
                                Forms\Components\TextInput::make('title')
                                    ->placeholder('Enter a title')
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function (Get $get, Set $set, string $operation, ?string $old, ?string $state) {
                                        if (($get('slug') ?? '') !== Str::slug($old) || $operation !== 'create') {
                                            return;
                                        }
                                        $set('slug', Str::slug($state));
                                    })
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->translateLabel()
                                    ->suffixAction(
                                        Actions\Action::make('translateTitle')
                                            ->icon('heroicon-o-language')
                                            ->iconSize('sm')
                                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                                            ->tooltip('Auto-translate title')
                                            ->action(function (Post $record, Set $set, Get $get) {
                                                try {
                                                    $currentLocale = app()->getLocale();
                                                    $oppositeLocale = $currentLocale === 'en' ? 'ar' : 'en';
                                                    $currentTitle = trim($get('title') ?? '');

                                                    if (empty($currentTitle)) {
                                                        $currentTitle = $record->getTranslation('title', $currentLocale, false);
                                                        $set('title', $currentTitle);
                                                    }

                                                    $translator = app(GeminiTranslationService::class);
                                                    $translated = $translator->translate(
                                                        $currentTitle,
                                                        $currentLocale,
                                                        $oppositeLocale
                                                    );

                                                    if ($translated) {
                                                        $record->setTranslation('title', $oppositeLocale, $translated);
                                                        $set('title', $translated);
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
                                Forms\Components\TextInput::make('slug')
                                    ->placeholder('Enter a slug')
                                    ->alphaDash()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->translateLabel(),
                            ]),
                            Forms\Components\Select::make('post_category_id')
                                ->label(trans('category'))
                                    ->reactive() // 1. This is the key
                                    ->afterStateUpdated(function (callable $set, $state) { // 2. This runs after a category is chosen

                                        // 3. We find the first post in the chosen category ($state is the category ID)
                                        $firstPostInCategory = Post::where('post_category_id', $state)
                                                                ->orderBy('order', 'asc')
                                                                ->first();

                                        // 4. If a post exists, we use $set to update the other field's value
                                        if ($firstPostInCategory) {
                                            $set('homepage_section_component', $firstPostInCategory->homepage_section_component);
                                        }
                                    })
                                ->relationship('postCategory', 'name')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('slug')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('description')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('order')->default(1)->maxLength(2)->translateLabel(),
                                    Forms\Components\Toggle::make('register_in_header')->default(true)->translateLabel(),
                                ])->editOptionForm([
                                    Forms\Components\TextInput::make('name')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('slug')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('description')->required()->maxLength(255)->translateLabel(),
                                    Forms\Components\TextInput::make('order')->default(1)->maxLength(2)->translateLabel(),
                                    Forms\Components\Toggle::make('register_in_header')->default(true)->translateLabel(),
                                ]),
                            Forms\Components\Builder::make('content')
                                ->columnSpanFull()
                                ->translateLabel()
                                ->default([['type' => 'markdown']])
                                ->blocks([
                                    Builder\Block::make('markdown')
                                        ->translateLabel()
                                        ->schema([
                                            Forms\Components\MarkdownEditor::make('content')->translateLabel(),
                                        ]),
                                    Builder\Block::make('figure')
                                        ->schema([
                                            CuratorPicker::make('image')
                                            ->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                                'application/pdf', // <-- Add this for PDF files
                                                'video/mp4',       // <-- Add this for MP4 video files
                                            ])->maxSize(20000)->translateLabel(),
                                            \Filament\Schemas\Components\Fieldset::make('Details')
                                                ->schema([
                                                    Forms\Components\TextInput::make('alt')->label('Alt Text')->placeholder('Enter alt text')->required()->maxLength(255)->translateLabel(),
                                                    Forms\Components\TextInput::make('caption')->placeholder('Enter a caption')->maxLength(255)->translateLabel(),
                                                ]),
                                        ]),
                                ]),
                        ]),

                    Wizard\Step::make(__('Media'))
                    ->icon('heroicon-o-paper-clip')
                        ->schema([
                            \Filament\Schemas\Components\Section::make(__('Featured Image'))
                                ->schema([
                                    CuratorPicker::make('image_id')->label('Featured Image')->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                                'image/SVG',
                                                'application/pdf', // <-- Add this for PDF files
                                                'video/mp4',       // <-- Add this for MP4 video files
                                            ])->maxSize(20000)->translateLabel(),
                                ]),
                            \Filament\Schemas\Components\Section::make(__('Additional Images'))
                                ->schema([
                                    CuratorPicker::make('images')->maxSize(20000)->acceptedFileTypes([
                                                'image/jpeg',
                                                'image/png',
                                                'image/webp',
                                                'image/SVG',
                                                'application/pdf', // <-- Add this for PDF files
                                                'video/mp4',       // <-- Add this for MP4 video files
                                            ])->multiple()->translateLabel(),
                                ]),

                            IconPicker::make('icon')->columns(3)->translateLabel()
                                ->sets([
                                    'heroicons',
                                    'forkawesome',
                                    'fontawesome-solid'
                                ]),
                        ]),

                    Wizard\Step::make(__('SEO & Publishing'))
                        ->icon('heroicon-o-photo')
                        ->schema([
                            \Filament\Schemas\Components\Section::make(__('SEO'))
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Forms\Components\Textarea::make('meta_title')->rows(2)->translateLabel()->maxLength(60),
                                    Forms\Components\Textarea::make('meta_description')->translateLabel()->rows(5)->maxLength(160),
                                ]),
                            \Filament\Schemas\Components\Section::make(__('Publishing'))
                                ->schema([
                                    Forms\Components\DatePicker::make('published_at')->label('Publish Date')->default(now())->required()->translateLabel(),
                                    Forms\Components\Toggle::make('is_published')->label('Published')->required()->translateLabel(),
                                    Forms\Components\Checkbox::make('show_in_home')->label('show_in_home')->translateLabel(),
                                ]),
                        ]),

                    Wizard\Step::make(__('Advanced'))
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            \Filament\Schemas\Components\Section::make(__('Author & Tags'))
                                ->schema([
                                    Forms\Components\Select::make('user_id')->label('Author')->relationship('user', 'name')->default(fn() => auth()->id())->searchable()->required()->translateLabel(),
                                    Select::make('tags')
                                        ->relationship('tags', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->translateLabel()
                                        ->searchable()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')->required()->maxLength(255)->translateLabel(),
                                        ])
                                        ->createOptionUsing(function (array $data) {
                                            $tag = Tag::firstOrCreate($data);
                                            return $tag->id;
                                        }),
                                ]),
                            \Filament\Schemas\Components\Section::make('metadata')
                                ->icon('heroicon-o-adjustments-horizontal')
                                ->schema([
                                    Forms\Components\KeyValue::make('metadata')
                                        ->keyLabel(__('Field Name'))
                                        ->valueLabel(__('Field Value'))
                                        ->translateLabel()
                                        ->reorderable(),
                                ]),
                        ]),
                ])
                ->skippable()
                ->columnSpanFull(),

                \Filament\Schemas\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('homepage_section_component')
                            ->label('Select Homepage Section')
                            ->visible(fn () => auth()->user()->hasRole('super_admin'))
                            ->options(self::getHomepageComponentOptions())
                            ->default(function (?Post $record): ?string {
                                if (!$record || !$record->post_category_id) {
                                    return null;
                                }
                                $firstPostInCategory = Post::where('post_category_id', $record->post_category_id)
                                                        ->orderBy('order', 'asc') // or 'published_at' or 'id'
                                                        ->first();
                                if ($firstPostInCategory && $firstPostInCategory->id !== $record->id) {
                                    return $firstPostInCategory->homepage_section_component;
                                }

                                return null;
                            })
                            ->reactive(),

                ]),
            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
        return \Taba\Crm\Components\Registry\ComponentRegistry::frontendSections();
    }

    /**
     * Get the table for the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),

                CuratorColumn::make('image')
                    ->circular()
                    ->size(32)
                    ->translateLabel(),

                Tables\Columns\ToggleColumn::make('show_in_home')
                    ->label('show_in_home')
                    ->translateLabel()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),

                Tables\Columns\TextColumn::make('content.0.data.content')
                    ->label(__('Content'))
                    ->translateLabel()
                    ->limit(50)
                    ->formatStateUsing(function (?string $state): string {
        // Limit the raw markdown string first, then convert
                    return Str::of($state ?? '')
                            ->limit(50)
                            ->markdown();
                    })
                    // 2. Tell the column to render the content as HTML
                    ->html()
                    // ->tooltip(function (?string $state): string {
                    //     // For the tooltip, convert the full content to markdown
                    //     return Str::of($state ?? '')->markdown();
                    // })
                    // ->formatStateUsing(fn($record) => $record->content[0]['data']['content']->markdown()->sanitizeHtml())

                    // ->tooltip(fn($record) => $record->content[0]['data']['content']->markdown()->sanitizeHtml()) // Shows full content on hover
                    ->size('small')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->badge()
                    ->sortable()
                    ->translateLabel(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable()
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('homepage_section_component')
                    ->label('Homepage Component')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('homepage_section_content')
                    ->label('Homepage Content')
                    ->formatStateUsing(fn(string $state): string => Str::limit(json_encode($state), 50))
                    ->tooltip(fn(string $state): string => json_encode($state))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->actions([
                // Actions\EditAction::make()
                //     ->translateLabel(),
                Actions\ActionGroup::make([
                    // ListPreviewAction::make(),
                    Actions\EditAction::make(),
                    Actions\ViewAction::make(),
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

    /**
     * Get the relationships for the resource.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $record = $page->getRecord();
        if (!$record) {
            return [];
        }

        $previous = static::getEloquentQuery()
            ->where('id', '<', $record->id)
            ->orderBy('id', 'desc')
            ->first();

        $next = static::getEloquentQuery()
            ->where('id', '>', $record->id)
            ->orderBy('id', 'asc')
            ->first();

        $navigationItems = [];

        // Add 'Previous' navigation item if it exists
        if ($previous) {
            $navigationItems[] = \Filament\Navigation\NavigationItem::make('Previous')
                ->label($previous->title)
                ->url(static::getUrl('edit', ['record' => $previous]))
                ->icon('heroicon-o-arrow-right')
                ->sort(1);
        }

        // Add 'Next' navigation item if it exists
        if ($next) {
            $navigationItems[] = \Filament\Navigation\NavigationItem::make('Next')
                ->label($next->title)
                ->url(static::getUrl('edit', ['record' => $next]))
                ->icon('heroicon-o-arrow-left')
                ->sort(2);
        }

        return $navigationItems;
    }

    /**
     * Get the pages for the resource.
     */
    public static function getPages(): array
    {
        // First, define the pages that should always be available.
        $pages = [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
        ];
        try {
            if (Post::count() > 1) {
                $pages['edit'] = Pages\EditPost::route('/{record}/edit');
            }
        } catch (\Throwable) {
            $pages['edit'] = Pages\EditPost::route('/{record}/edit');
        }

        // Return the final array of pages.
        return $pages;
    }
}
