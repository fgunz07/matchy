<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

final class UserIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = User::whereNot('id', $request->user()->id)
            ->withCount(['likers']);

        parent::__construct($query, $request);

        $this->allowedFilters()
            ->allowedSorts()
            ->allowedIncludes('likers')
            ->defaultSorts('-created_at');
    }
}
