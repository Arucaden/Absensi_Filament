<?php

namespace App\Filament\Resources\SetThrResource\Pages;

use App\Filament\Resources\SetThrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetThr extends EditRecord
{
    protected static string $resource = SetThrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
