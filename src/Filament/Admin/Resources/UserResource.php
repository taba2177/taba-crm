<?php

namespace Taba\Crm\Filament\Admin\Resources;

use Taba\Crm\Filament\Admin\Resources\UserResource\Pages;
use Taba\Crm\Models\User;
use Taba\Crm\Concerns\HasNavigationVisibility;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasNavigationVisibility;

    /**
     * The resource model.
     */
    protected static ?string $model = User::class;

    /**
     * The resource navigation icon.
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    /**
     * The settings navigation group.
     */
    protected static string|\UnitEnum|null $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('Collections');
    }
    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('Users'); // Translate your desired label
    }
    /**
     * The settings navigation sort order.
     */
    protected static ?int $navigationSort = 1;

    // protected static bool $shouldRegisterNavigation = false;


    /**
     * Get the navigation badge for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    /**
     * The resource form.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->translateLabel(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->translateLabel(),

                Forms\Components\TextInput::make('password')
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->password()
                    ->confirmed()
                    ->maxLength(255)
                    ->translateLabel(),

                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirm password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),

                // Using CheckboxList Component
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable(),
            ]);
    }

    /**
     * The resource table.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->translateLabel(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->translateLabel(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Actions\CreateAction::make(),
            ]);
    }

    /**
     * The resource relation managers.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * The resource pages.
     */
    public static function getPages(): array
    {
        return [

           'index' => Pages\ListUsers::route('/'),
        ];
    }
}
