<?php
namespace App\Filament\Resources\KaryawanResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\KaryawanResource;
use Illuminate\Routing\Router;


class KaryawanApiService extends ApiService
{
    protected static string | null $resource = KaryawanResource::class;

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
