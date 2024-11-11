<?php
namespace App\Filament\Resources\PosisiResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\PosisiResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = PosisiResource::class;
    public static bool $public = true;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        $query = QueryBuilder::for($query)
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
