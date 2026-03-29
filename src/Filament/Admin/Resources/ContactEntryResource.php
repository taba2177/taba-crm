<?php

namespace Taba\Crm\Filament\Admin\Resources;

use Taba\Crm\Filament\Admin\Resources\ContactEntryResource\Pages;
use Taba\Crm\Models\ContactEntry;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ContactEntryResource extends Resource
{

    protected static ?string $model = ContactEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }
    // protected static ?string $navigationGroup = 'Contact';
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }

    public static function getNavigationLabel(): string
    {
        return __('Contact Entries');
    }
    public static function getModelLabel(): string
    {
        return __('Contact Entry');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Contact Entries');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\TextEntry::make('created_at')
                ->label('Date')
                ->translateLabel()
                ->columnSpanFull(),

            Infolists\Components\TextEntry::make('name')
                ->translateLabel()
                ->columnSpanFull(),

            Infolists\Components\TextEntry::make('email')
                ->translateLabel()
                ->columnSpanFull(),

            Infolists\Components\TextEntry::make('message')
                ->formatStateUsing(fn ($state) => new HtmlString(nl2br($state)))
                ->translateLabel()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->translateLabel()->sortable(),
                Tables\Columns\TextColumn::make('email')->translateLabel()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactEntries::route('/'),
            'view' => Pages\ViewContactEntry::route('/{record}'),
            // 'create' => Pages\CreateContactEntry::route('/create'),
            // 'edit' => Pages\EditContactEntry::route('/{record}/edit'),
        ];
    }
}
