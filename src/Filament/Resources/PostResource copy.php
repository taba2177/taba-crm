<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\CustomTagsInput;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
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
use App\Services\GeminiTranslationService;
use App\Models\Tag;
use Filament\Forms\Components\Select;
use Filament\Resources\Concerns\Translatable;
use App\Models\MetadataFillter;
use Illuminate\Support\Facades\DB;

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
                                // Forms\Components\TextInput::make('title')
                                //     ->placeholder('Enter a title')
                                //     ->live()
                                //     ->afterStateUpdated(function (Get $get, Set $set, string $operation, ?string $old, ?string $state) {
                                //         if (($get('slug') ?? '') !== Str::slug($old) || $operation !== 'create') {
                                //             return;
                                //         }
                                //         $set('slug', Str::slug($state));
                                //     })
                                //     ->required()
                                //     ->maxLength(255)
                                //     ->autofocus()
                                //     ->translateLabel()
                                //     ->suffixAction(
                                //         Forms\Components\Actions\Action::make('translateTitle')
                                //             ->icon('heroicon-o-language')
                                //             ->tooltip('Translate title')
                                //             ->action(function (Forms\Components\TextInput $component, Forms\Set $set, Post $record) {
                                //                 $currentLocale = app()->getLocale();
                                //                 $oppositeLocale = $currentLocale === 'en' ? 'ar' : 'en';

                                //                 // Get current title in active locale
                                //                 $currentTitle = $component->getState();

                                //                 // Don't translate if empty
                                //                 if (empty(trim($currentTitle))) {
                                //                     Notification::make()
                                //                         ->title('Cannot translate')
                                //                         ->body('Title is empty')
                                //                         ->warning()
                                //                         ->send();
                                //                     return;
                                //                 }

                                //                 // Check if opposite locale already has content
                                //                 $oppositeTitle = $record->getTranslation('title', $oppositeLocale, false);
                                //                 if (!empty($oppositeTitle)) {
                                //                     Notification::make()
                                //                         ->title('Already translated')
                                //                         ->body("Title already exists in {$oppositeLocale}")
                                //                         ->info()
                                //                         ->send();
                                //                     return;
                                //                 }

                                //                 // Translate using your service
                                //                 $translator = app(GeminiTranslationService::class);
                                //                 $translated = $translator->translate(
                                //                     $currentTitle,
                                //                     $currentLocale,
                                //                     $oppositeLocale
                                //                 );

                                //                 if ($translated) {
                                //                     $record->setTranslation('title', $oppositeLocale, $translated);
                                //                     $record->save();

                                //                     Notification::make()
                                //                         ->title('Title translated')
                                //                         ->body("Title translated to {$oppositeLocale}")
                                //                         ->success()
                                //                         ->send();
                                //                 } else {
                                //                     Notification::make()
                                //                         ->title('Translation failed')
                                //                         ->body('Could not translate title')
                                //                         ->danger()
                                //                         ->send();
                                //                 }
                                //             })
                                //     ),
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
                                    ]),


                                Forms\Components\Builder::make('content')
                                    ->columnSpanFull()
                                    ->default([
                                        ['type' => 'markdown'],
                                    ])
                                    ->blocks([
                                        Builder\Block::make('markdown')
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
                            ]),

                        Forms\Components\Section::make()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('slug')
                                    ->placeholder('Enter a slug')
                                    ->alphaDash()
                                    ->required()
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
                                            ->minLength(56)
                                            ->translateLabel()
                                            ->maxLength(60),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->translateLabel()
                                            ->rows(5)
                                            ->minLength(155)
                                            ->maxLength(160),
                                    ]),

                                Forms\Components\Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->default(fn() => auth()->id())
                                    ->searchable()
                                    ->required()
                                    ->translateLabel(),

                                // switch sevices ,our_works, blog, faq metadata fileds
                                // Forms\Components\Repeater::make('metadata')
                                // ->schema([
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
                                // ])
                                // ->columnSpanFull()
                                // ->translateLabel(),

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
            ]);
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

                //add content with tooltip


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
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('post_category_id')
                //     ->label('Category')
                //     ->relationship('postCategory', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->translateLabel(),
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