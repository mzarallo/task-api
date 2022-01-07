<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetAllBoards;
use App\Http\Resources\BoardResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BoardController extends Controller
{
    public function all(GetAllBoards $getAllBoards): AnonymousResourceCollection
    {
        return BoardResource::collection($getAllBoards->run(['author']));
    }
}
