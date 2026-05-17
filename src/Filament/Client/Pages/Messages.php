<?php

namespace Taba\Crm\Filament\Client\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Taba\Crm\Models\ContactEntry;

class Messages extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?int $navigationSort = 80;
    protected string $view = 'crm::client.messages';

    public static function getNavigationGroup(): ?string
    {
        return __('إدارة الموقع');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ContactEntry::count();
        return $count ?: null;
    }

    public static function getNavigationLabel(): string
    {
        return __('الرسائل');
    }

    public static function getSlug(): string
    {
        return 'messages';
    }

    public function getTitle(): string
    {
        return __('الرسائل');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactEntry::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('التاريخ'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('البريد الإلكتروني'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('الهاتف')),
                Tables\Columns\TextColumn::make('message')
                    ->label(__('الرسالة'))
                    ->limit(50),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('عرض'))
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (ContactEntry $record) => __('رسالة من') . ' ' . $record->name)
                    ->modalContent(fn (ContactEntry $record) => view('crm::client.message-detail', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('إغلاق')),
            ]);
    }
}
