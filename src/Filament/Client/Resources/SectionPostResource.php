<?php

namespace Taba\Crm\Filament\Client\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class SectionPostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static bool $shouldRegisterNavigation = false;

    public static function getSlug(): string
    {
        return 'section-posts';
    }

    public static function getModelLabel(): string
    {
        return __('عنصر');
    }

    public static function getPluralModelLabel(): string
    {
        return __('العناصر');
    }

    public static function form(Form $form): Form
    {
        $categoryId = request()->route('category');
        $category = $categoryId ? PostCategory::find($categoryId) : null;
        $component = null;

        if ($category && $category->section_component && ComponentRegistry::has($category->section_component)) {
            $component = ComponentRegistry::resolve($category->section_component);
        }

        $fields = $component ? $component->itemFields() : [];

        $basicFields = [];
        $mediaFields = [];
        $extraFields = [];

        foreach ($fields as $field) {
            $name = $field->getName();
            if (in_array($name, ['title', 'content', 'subtitle'])) {
                $basicFields[] = $field;
            } elseif (in_array($name, ['image', 'image_id'])) {
                $mediaFields[] = $field;
            } else {
                $extraFields[] = $field;
            }
        }

        $schema = [];

        if (!empty($basicFields)) {
            $schema[] = Forms\Components\Section::make(__('المعلومات الأساسية'))
                ->schema($basicFields)
                ->collapsible();
        }

        if (!empty($mediaFields)) {
            $schema[] = Forms\Components\Section::make(__('الوسائط'))
                ->schema($mediaFields)
                ->collapsible();
        }

        if (!empty($extraFields)) {
            $schema[] = Forms\Components\Section::make(__('إعدادات إضافية'))
                ->schema($extraFields)
                ->collapsible();
        }

        $schema[] = Forms\Components\Hidden::make('post_category_id')->default($categoryId);

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('العنوان'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image.path')
                    ->label(__('الصورة'))
                    ->circular(),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('الترتيب'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label(__('منشور'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('آخر تحديث'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages\ListSectionPosts::route('/{category}'),
            'create' => \Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages\CreateSectionPost::route('/{category}/create'),
            'edit' => \Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages\EditSectionPost::route('/{category}/{record}/edit'),
            'view' => \Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages\ViewSectionPost::route('/{category}/{record}'),
        ];
    }
}
