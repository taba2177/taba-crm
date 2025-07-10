<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Filament\Resources\PostCategoryResource\RelationManagers;
use App\Models\PostCategory;
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

class PostCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = \Taba\Crm\Models\PostCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->translateLabel(),

                Forms\Components\TextInput::make('subtitle')
                    ->translateLabel(),

                Forms\Components\Section::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('section_component')
                            ->label('Select Section')
                            ->options(self::getHomepageComponentOptions())
                            ->reactive(),

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
            //
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