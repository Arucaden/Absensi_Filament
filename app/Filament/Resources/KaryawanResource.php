<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use App\Models\AdminActivityLog;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelola Karyawan';

    protected static ?string $label = 'Kelola Karyawan';

    protected static ?string $pluralLabel = 'Kelola Karyawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(16),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama lengkap')
                    ->required()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('tanggal_lahir')
                ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan'
                ]),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('no_telepon')
                    ->tel()
                    ->maxLength(15)
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->required()
                    ->default(Carbon::now('Asia/Jakarta')),
                Forms\Components\TextInput::make('foto_path')
                    ->maxLength(255),
                Forms\Components\Select::make('posisi_id')
                    ->relationship('posisi', 'posisi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                Tables\Columns\TextColumn::make('agama'),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('posisi.posisi')
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

    protected static function afterCreate(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'from' => null, // Tidak ada data sebelumnya
            'to' => json_encode($record->getAttributes()),
        ]);
    }

    protected static function afterUpdate(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'from' => json_encode($record->getOriginal()),
            'to' => json_encode($record->getAttributes()),
        ]);
    }

    protected static function afterDelete(Karyawan $record): void
    {
        AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'from' => json_encode($record->getAttributes()),
            'to' => null, // Karena data ini dihapus
        ]);
    }

    public static function getRecordRouteKeyName(): string
    {
    return 'id_karyawan';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
