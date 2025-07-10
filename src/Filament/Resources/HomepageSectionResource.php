<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Filament\Resources\HomepageSectionResource\RelationManagers;
use App\Models\HomepageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Placeholder;

use App\Models\PostCategory;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('component_view')
                    ->options([
                        'hero' => 'Hero',
                        'brand-marque' => 'Brand Marque',
                        'services-list' => 'Services List',
                        'portfolio' => 'Portfolio',
                        'services-features' => 'Services Features',
                        'services-carousel' => 'Services Carousel',
                        'faq' => 'FAQ',
                        'services-grid' => 'Services Grid',
                        'latest-news' => 'Latest News',
                    ])
                    ->reactive()
                    ->required(),
                Select::make('post_category_id')
                    ->relationship('postCategory', 'name')
                    ->reactive(),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Repeater::make('additional_data')
                    ->schema([
                        Forms\Components\TextInput::make('title')->nullable(),
                        Forms\Components\TextInput::make('subtitle')->nullable(),
                        Forms\Components\RichEditor::make('content')->nullable(),
                        Forms\Components\FileUpload::make('image')->nullable(),
                    ])
                    ->defaultItems(1)
                    ->columnSpan('full'),
                Placeholder::make('preview_section')
                    ->label('Preview')
                    ->content(function (\Filament\Forms\Get $get) {
                        $postCategory = \Taba\Crm\Models\PostCategory::find($get('post_category_id'));
                        $componentView = 'components.homepage.' . $get('component_view');

                        if (!$get('component_view')) {
                            return '';
                        }

                        return view('filament.forms.components.preview', [
                            'posts' => $postCategory ? $postCategory->posts : collect(),
                            'component' => $componentView,
                        ]);
                    })
                    ->hidden(fn (\Filament\Forms\Get $get) => !$get('component_view')),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('component_view'),
                TextColumn::make('postCategory.name'),
                TextColumn::make('order'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}