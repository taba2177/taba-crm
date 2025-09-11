<?php

namespace Taba\Crm\Filament\Resources;

use Illuminate\Database\Eloquent\Model;

use Taba\Crm\Filament\Resources\PostResource\Pages;
use Taba\Crm\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Taba\Crm\Services\GeminiTranslationService;
use Taba\Crm\Models\Tag;
use Filament\Forms\Components\Select;
use Filament\Resources\Concerns\Translatable;
use Taba\Crm\Models\MetadataFillter;
use Illuminate\Support\Facades\File;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Filament\Forms\Components\Wizard;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Pages\Page;
use Taba\Crm\Models\PostCategory;
// SubNavigationPosition
use Filament\Pages\SubNavigationPosition;
use Taba\Crm\Filament\Clusters\Posts;


class PostResource extends Resource
{
    use Translatable;
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
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // sub navigation posistin
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * The resource navigation group.
     */
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }
    protected static ?string $navigationLabel = null;

    // public static function getNavigationParentItem(): ?string
    // {
    //     return __('posts');
    // }

    // // Add a method to dynamically generate navigation items for each category
    // public static function getNavigationItems(): array
    // {
    //     $items = [];

    //     // Add an 'All Posts' navigation item. This will be active when no category is selected.
    //     $items[] = NavigationItem::make(__('All Posts'))
    //         ->url(static::getUrl('index'))
    //         ->isActiveWhen(fn () => !request()->query('category'));

    //     $categories = PostCategory::all();

    //     foreach ($categories as $category) {
    //         $items[] = NavigationItem::make($category->name)
    //             ->label($category->name)
    //             ->url(static::getUrl('index', ['category' => $category->id]))
    //             ->icon('heroicon-o-folder') // Use a folder icon for categories
    //             ->isActiveWhen(fn () => request()->query('category') == $category->id);
    //     }

    //     return $items;
    // }
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
        return __('Custom Page Subheading');
    }

    public static function getModelLabel(): string
    {
        return __('Posts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Posts');
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
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make(__('Content'))
                    ->icon('heroicon-o-pencil')
                    ->schema([
                            Forms\Components\Grid::make(2)->schema([
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
                                        Forms\Components\Actions\Action::make('translateTitle')
                                            ->icon('heroicon-o-language')
                                            ->iconSize('sm')
                                            ->tooltip('Auto-translate title')
                                            ->action(function (Post $record, Forms\Set $set, Get $get) {
                                                try {
                                                    $currentLocale = \Filament\Resources\Concerns\Translatable::getDefaultTranslatableLocale();
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
                                            CuratorPicker::make('image')->required()->translateLabel(),
                                            Forms\Components\Fieldset::make('Details')
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
                            Forms\Components\Section::make(__('Featured Image'))
                                ->schema([
                                    CuratorPicker::make('image_id')->label('Featured Image')->translateLabel(),
                                ]),
                            Forms\Components\Section::make(__('Additional Images'))
                                ->schema([
                                    CuratorPicker::make('images')->multiple()->translateLabel(),
                                ]),
                        ]),

                    Wizard\Step::make(__('SEO & Publishing'))
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make(__('SEO'))
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Forms\Components\Textarea::make('meta_title')->rows(2)->translateLabel()->maxLength(60),
                                    Forms\Components\Textarea::make('meta_description')->translateLabel()->rows(5)->maxLength(160),
                                ]),
                            Forms\Components\Section::make(__('Publishing'))
                                ->schema([
                                    Forms\Components\DatePicker::make('published_at')->label('Publish Date')->default(now())->required()->translateLabel(),
                                    Forms\Components\Toggle::make('is_published')->label('Published')->required()->translateLabel(),
                                ]),
                        ]),

                    Wizard\Step::make(__('Advanced'))
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Forms\Components\Section::make(__('Author & Tags'))
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
                                            $tag = Tag::firstorcreate($data);
                                            return $tag->id;
                                        }),
                                ]),
                            Forms\Components\Section::make(__('Metadata'))
                                ->schema([
                                    Forms\Components\KeyValue::make(__('Metadata'))
                                        ->keyLabel(__('Field Name'))
                                        ->valueLabel(__('Field Value'))
                                        ->translateLabel()
                                        ->reorderable(),
                                ]),
                        ]),
                ])
                ->skippable()
                ->columnSpanFull(),
            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
                // // Get the base path for the 'crm' view namespace
        // $crmViewPath = \Illuminate\Support\Facades\View::getFinder()->getHints()['crm'][0];        // // Now, construct the correct full paths by appending your sbdirectories
        // $componentPath = $crmViewPath . '/livewire/post/templates';
        // $componentSection = $crmViewPath . '/components/homepage';
        $componentPath = resource_path('views/livewire/post/templates');
        $componentSection = resource_path('views/components/homepage');
        $files = File::files($componentPath, $componentSection);
        $files = array_merge($files, File::files($componentSection));
        $options = [];

        foreach ($files as $file) {
            $name = Str::before($file->getFilename(), '.blade.php');
            $options[$name] = Str::title(str_replace('-', ' ', $name));
        }

        return $options;
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

                Tables\Columns\TextColumn::make('content')
                    ->translateLabel()
                    ->limit(50) // Limits content to 50 characters, appends "..." automatically
                    ->tooltip(fn($record) => $record->content) // Shows full content on hover
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
                // Tables\Actions\EditAction::make()
                //     ->translateLabel(),
                Tables\Actions\ActionGroup::make([
                    // ListPreviewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->translateLabel(),
                ]),
            ]);
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
        // Here is the conditional logic.
        // We check the total count of Post records in the database.
        if (Post::count() > 1) {
            // If the count is greater than one, we add the 'edit' page
            // to our array of pages.
            $pages['edit'] = Pages\EditPost::route('/{record}/edit');
        }

        // Return the final array of pages.
        return $pages;
    }
}
