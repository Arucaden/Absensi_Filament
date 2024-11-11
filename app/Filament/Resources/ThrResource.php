<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThrResource\Pages;
use App\Filament\Resources\ThrResource\RelationManagers;
use App\Models\Thr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mpdf\Mpdf;

class ThrResource extends Resource
{
    protected static ?string $model = Thr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'List THR';
    protected static ?string $label = 'THR';

    protected static ?string $pluralLabel = 'Tunjangan Hari Raya';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('karyawan.posisi.posisi')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('thr')
                ->label('Besaran THR')
                ->searchable()
                ->sortable()
                ->formatStateUsing(fn ($state) => 'Rp. ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->url(route('export-thr'))
                    ->openUrlInNewTab(),
                // Mengubah ekspor PDF menjadi action yang memanggil controller
                Tables\Actions\Action::make('Export PDF')
                    ->label('Export PDF')
                    ->url(route('thr.exportPDF')) // Pastikan route ini ada
                    ->openUrlInNewTab(),
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
            ])
;
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
            'index' => Pages\ListThrs::route('/'),
            'create' => Pages\CreateThr::route('/create'),
            'edit' => Pages\EditThr::route('/{record}/edit'),
        ];
    }
}
