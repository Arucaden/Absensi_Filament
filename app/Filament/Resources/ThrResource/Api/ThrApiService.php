<?php
namespace App\Filament\Resources\ThrResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ThrResource;
use Illuminate\Routing\Router;


class ThrApiService extends ApiService
{
    protected static string | null $resource = ThrResource::class;


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
