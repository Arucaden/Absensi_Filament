<?php
namespace App\Filament\Resources\AbsensiResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\AbsensiResource;
use Illuminate\Routing\Router;


class AbsensiApiService extends ApiService
{
    protected static string | null $resource = AbsensiResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
