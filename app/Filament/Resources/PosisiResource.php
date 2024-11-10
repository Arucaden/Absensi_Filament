<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PosisiResource\Pages;
use App\Filament\Resources\PosisiResource\RelationManagers;
use App\Models\Posisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;


class PosisiResource extends Resource
{
    protected static ?string $model = Posisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kelola Posisi';


    protected static ?string $pluralLabel = 'Kelola Posisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('posisi')
                    ->required()
                    ->maxLength(50)
                    ->rule(function ($record) {
                        return Rule::unique('posisis', 'posisi')
                            ->ignore($record->id_posisi ?? null, 'id_posisi'); // Ensure correct ID is ignored
                    }),
                Forms\Components\TextInput::make('jam_kerja_per_hari')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('hari_kerja_per_minggu')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('posisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_kerja_per_hari')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hari_kerja_per_minggu')
                    ->numeric()
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
            'index' => Pages\ListPosisis::route('/'),
            'create' => Pages\CreatePosisi::route('/create'),
            'edit' => Pages\EditPosisi::route('/{record}/edit'),
        ];
    }
}
