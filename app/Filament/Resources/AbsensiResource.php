<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;


class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelola Absensi';

    protected static ?string $label = 'Kelola Absensi';

    protected static ?string $pluralLabel = 'Kelola Absensi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('karyawan_id')
                    ->relationship('karyawan', 'nama'),
                Forms\Components\DatePicker::make('tanggal')
                ->default(now()->timezone('Asia/Jakarta')->toDateString()) // sets the default date to today's date in Indonesia
                ->required()
                ->label('Tanggal'),
                Forms\Components\TextInput::make('jam_masuk'),
                Forms\Components\TextInput::make('jam_keluar'),
                Forms\Components\TextInput::make('status'),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),
                Tables\Columns\TextColumn::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('H:i')),
                Tables\Columns\TextColumn::make('durasi'),
                Tables\Columns\TextColumn::make('status'),
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
                Tables\Filters\Filter::make('tanggal_hari_ini')
                    ->label('Tanggal Hari Ini')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::today())),

                Tables\Filters\Filter::make('kemarin')
                    ->label('Kemarin')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', Carbon::yesterday())),

                Tables\Filters\Filter::make('seminggu_terakhir')
                    ->label('Seminggu Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subWeek())),

                Tables\Filters\Filter::make('sebulan_terakhir')
                    ->label('Sebulan Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subMonth())),

                Tables\Filters\Filter::make('setahun_terakhir')
                    ->label('Setahun Terakhir')
                    ->query(fn (Builder $query) => $query->whereDate('tanggal', '>=', Carbon::today()->subYear())),

                Tables\Filters\Filter::make('status_hadir')
                    ->label('Hadir')
                    ->query(fn (Builder $query) => $query->where('status', 'Hadir')),

                Tables\Filters\Filter::make('status_sakit')
                    ->label('Sakit')
                    ->query(fn (Builder $query) => $query->where('status', 'Sakit')),

                Tables\Filters\Filter::make('status_izin')
                    ->label('Izin')
                    ->query(fn (Builder $query) => $query->where('status', 'Izin')),

                Tables\Filters\Filter::make('status_alpa')
                    ->label('Alpa')
                    ->query(fn (Builder $query) => $query->where('status', 'Alpa')),
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
