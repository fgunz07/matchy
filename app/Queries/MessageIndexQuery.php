<?php

namespace App\Queries;

use App\Models\Message;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

final class MessageIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Message::query();

        parent::__construct($query, $request);

        $this->allowedFilters()
            ->allowedSorts()
            ->allowedIncludes()
            ->defaultSorts('-created_at');
    }
}
