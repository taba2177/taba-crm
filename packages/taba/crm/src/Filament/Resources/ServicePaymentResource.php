<?php

namespace Taba\Crm\Filament\Resources;

use Taba\Crm\Filament\Resources\ServicePaymentResource\Pages;
use Taba\Crm\Filament\Resources\ServicePaymentResource\RelationManagers;
use Taba\Crm\Models\ServicePayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicePaymentResource extends Resource
{
    protected static ?string $model = ServicePayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = null;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Payment Services');
    }

    public static function getNavigationLabel(): string
    {
        return __('Service Payments');
    }

    public static function getModelLabel(): string
    {
        return __('Service Payment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Service Payments');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('client')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->translateLabel(),
                Forms\Components\Select::make('post_id')
                    ->relationship('post', 'title')
                    ->translateLabel(),
                Forms\Components\TextInput::make('moyasar_payment_id')
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->translateLabel(),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('payment_method')
                    ->translateLabel(),
                Forms\Components\TextInput::make('description')
                    ->translateLabel(),
                Forms\Components\TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->translateLabel(),
                Forms\Components\Textarea::make('metadata')
                    ->columnSpanFull()
                    ->translateLabel(),
                Forms\Components\DateTimePicker::make('refunded_at')
                    ->translateLabel(),
                Forms\Components\TextInput::make('refunded_amount')
                    ->numeric()
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('post.title')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('moyasar_payment_id')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('fee')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('refunded_at')
                    ->dateTime()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('refunded_amount')
                    ->numeric()
                    ->sortable()
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
            'index' => Pages\ListServicePayments::route('/'),
            'view' => Pages\ViewServicePayments::route('/{record}'),
            // 'create' => Pages\CreateServicePayment::route('/create'),
            // 'edit' => Pages\EditServicePayment::route('/{record}/edit'),
        ];
    }
}