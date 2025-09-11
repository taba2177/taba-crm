<?php

namespace Taba\Crm\Filament\Resources\PostCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Taba\Crm\Models\Post;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms\Components\Builder;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Taba\Crm\Services\GeminiTranslationService;
use Taba\Crm\Models\Tag;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;


class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

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


    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),

                CuratorColumn::make('image')
                    ->circular()
                    ->size(32)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('content.content')
                    ->translateLabel()
                    ->limit(50) // Limits content to 50 characters, appends "..." automatically
                    ->tooltip(fn(string $state): string => json_encode($state["content"] ?? '-')) // Shows full content on hover
                    ->size('small')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable()
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->label(__('Tags'))
                    ->badge()
                    ->separator(',')
                    ->sortable()
                    // ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('homepage_section_component')
                    ->label('Homepage Component')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DissociateAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                    // Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
        //     ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
        //         SoftDeletingScope::class,
        //     ])
        // );
    }
}
