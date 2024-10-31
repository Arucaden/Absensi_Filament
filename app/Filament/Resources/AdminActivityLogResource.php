<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminActivityLogResource\Pages;
use App\Filament\Resources\AdminActivityLogResource\RelationManagers;
use App\Models\AdminActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminActivityLogResource extends Resource
{
    protected static ?string $model = AdminActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('action')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('result')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('action_time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->searchable(),
                Tables\Columns\TextColumn::make('result')
                    ->searchable(),
                Tables\Columns\TextColumn::make('action_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAdminActivityLogs::route('/'),
            'create' => Pages\CreateAdminActivityLog::route('/create'),
            'edit' => Pages\EditAdminActivityLog::route('/{record}/edit'),
        ];
    }
}
