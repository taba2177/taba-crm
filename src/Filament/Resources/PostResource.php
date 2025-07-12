<?php

namespace Taba\Crm\Filament\Resources;

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

    /**
     * The resource icon.
     */
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    /**
     * The resource navigation group.
     */
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }
    protected static ?string $navigationLabel = null;

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
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Section::make()
                            ->columnSpan(2)
                            ->schema([

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
                                                        // get ar title
                                                        $currentTitle = $record->getTranslation('title', $currentLocale, false);
                                                        $set('title', $currentTitle);
                                                        // return;
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
                                            ->hidden(fn(?Post $record): bool => !$record?->exists)
                                    ),

                                Forms\Components\Select::make('post_category_id')
                                    ->label(trans('category'))
                                    ->relationship('postCategory', 'name')
                                    ->columnSpan(1)
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),
                                        // slug
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),
                                        //descrioption
                                        Forms\Components\TextInput::make('description')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),

                        Forms\Components\TextInput::make('order')
                            ->default(1)
                            ->maxLength(2)
                            ->translateLabel(),


                        Forms\Components\Toggle::make('register_in_header')
                            ->default(true)
                            ->translateLabel(),

                    ])->editOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),
                                        // slug
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),

                                        //descrioption
                                        Forms\Components\TextInput::make('description')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),

                        Forms\Components\TextInput::make('order')
                            ->default(1)
                            ->maxLength(2)
                            ->translateLabel(),


                        Forms\Components\Toggle::make('register_in_header')
                            ->default(true)
                            ->translateLabel(),


                    ]),


                                Forms\Components\Builder::make('content')
                                    ->columnSpanFull()
                                    ->translateLabel()
                                    ->default([
                                        ['type' => 'markdown'],
                                    ])
                                    ->blocks([
                                        Builder\Block::make('markdown')
                                            ->translateLabel()
                                            ->schema([
                                                Forms\Components\MarkdownEditor::make('content')
                                                    ->translateLabel(),
                                            ]),

                                        Builder\Block::make('figure')
                                            ->schema([
                                                CuratorPicker::make('image')
                                                    ->required()
                                                    ->translateLabel(),
                                                Forms\Components\Fieldset::make()
                                                    ->label('Details')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('alt')
                                                            ->label('Alt Text')
                                                            ->placeholder('Enter alt text')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->translateLabel(),

                                                        Forms\Components\TextInput::make('caption')
                                                            ->placeholder('Enter a caption')
                                                            ->maxLength(255)
                                                            ->translateLabel(),
                                                    ]),

                                            ]),
                                    ]),
                    CuratorPicker::make('images')
                        ->multiple()
                        ->translateLabel(),
                    ]),

                        Forms\Components\Section::make()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('slug')
                                    ->placeholder('Enter a slug')
                                    ->alphaDash()

                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->translateLabel(),

                                Forms\Components\Section::make(__('SEO'))
                                    ->icon('heroicon-o-photo')
                                    ->columnSpan(1)
                                    ->columns(1)
                                    ->schema([
                                        Forms\Components\Textarea::make('meta_title')
                                            ->rows(2)
                                            ->translateLabel()
                                            ->maxLength(60),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->translateLabel()
                                            ->rows(5)
                                            ->maxLength(160),
                                    ]),

                                Forms\Components\Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->default(fn() => auth()->id())
                                    ->searchable()
                                    ->required()
                                    ->translateLabel(),

                    Forms\Components\Repeater::make('metadata')
                        ->label('Metadata')
                        ->schema([
                            Forms\Components\Select::make('key')
                                ->label('Key')
                                ->options(MetadataFillter::all()->pluck('key', 'id'))
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('key')
                                        ->required()
                                        ->maxLength(255)
                                        ->translateLabel(),
                                    Forms\Components\TagsInput::make('value')
                                        ->required()
                                        ->translateLabel(),
                                ])
                                ->createOptionUsing(function (array $data) {
                                    $metadata = MetadataFillter::create([
                                        'key' => $data['key'],
                                        'value' => $data['value'],
                                'key_string' => $data['key'],
                                    ]);
                                    return $metadata->id;
                                })
                                ->editOptionAction(
                                    fn(Forms\Components\Actions\Action $action) => $action
                                        ->form([
                                            Forms\Components\TextInput::make('key')
                                                ->required()
                                                ->maxLength(255)
                                                ->translateLabel(),
                                            Forms\Components\TagsInput::make('value')
                                                ->required()
                                                ->translateLabel(),
                                        ])
                                        ->fillForm(function (string $state): array {
                                            $record = MetadataFillter::find($state);
                                            return [
                                                'key' => $record?->key,
                                                'value' => $record?->value,
                                    'key_string' => $record?->key,

                                ];
                                        })
                                        ->action(function (array $data, string $state): void {
                                            MetadataFillter::find($state)->update($data);
                                        })
                                )
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set, string $operation, ?string $old, ?string $state) {
                                    if ($operation === 'create') {
                                        $set('value', []);
                                    }
                                })
                                ->translateLabel(),
                            Forms\Components\Select::make('value')
                                ->label('Value')
                                ->options(function (Get $get) {
                                    $keyId = $get('key');
                                    if (!$keyId) return [];
                                    $record = MetadataFillter::find($keyId);
                                    return collect($record?->value, true)
                                        ->mapWithKeys(fn($item) => [$item => $item])
                                        ->toArray();
                                })
                                ->searchable()
                                ->live(),
                        ])
                        ->addActionLabel('Add Metadata')
                        ->translateLabel(),


                    CuratorPicker::make('image_id')
                                    ->label('Featured Image')
                                    ->translateLabel(),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->required()
                                    ->translateLabel(),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->required()
                                    ->translateLabel(),


                    Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->translateLabel()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->translateLabel(),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        $tag = Tag::firstorcreate($data);
                                        return $tag->id;
                                    }),


                            ]),

                    ]),
                Forms\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('homepage_section_component')
                            ->label('Select Homepage Section')
                            ->options(self::getHomepageComponentOptions())
                            ->reactive(),

                ]),
            ]);
    }

    protected static function getHomepageComponentOptions(): array
    {
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

    /**
     * Get the pages for the resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
