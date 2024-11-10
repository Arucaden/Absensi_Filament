<?php
namespace App\Filament\Resources\AdminActivityLogResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\AdminActivityLogResource;
use Illuminate\Routing\Router;


class AdminActivityLogApiService extends ApiService
{
    protected static string | null $resource = AdminActivityLogResource::class;

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
