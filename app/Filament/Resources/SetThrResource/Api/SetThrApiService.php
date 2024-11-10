<?php
namespace App\Filament\Resources\SetThrResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\SetThrResource;
use Illuminate\Routing\Router;


class SetThrApiService extends ApiService
{
    protected static string | null $resource = SetThrResource::class;

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
