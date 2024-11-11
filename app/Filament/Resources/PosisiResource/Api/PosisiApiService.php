<?php
namespace App\Filament\Resources\PosisiResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\PosisiResource;
use Illuminate\Routing\Router;


class PosisiApiService extends ApiService
{
    protected static string | null $resource = PosisiResource::class;

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
