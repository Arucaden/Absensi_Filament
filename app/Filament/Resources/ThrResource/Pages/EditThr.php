<?php

namespace App\Filament\Resources\ThrResource\Pages;

use App\Filament\Resources\ThrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThr extends EditRecord
{
    protected static string $resource = ThrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
