<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\GameStartRequest;
use App\Models\Game;
/**
 * @group Game
 */
class GameController extends Controller
{
    public function start(GameStartRequest $request)
    {
        $game = new Game();
        $game->length = $request->length ?? 3;
        $game->number = generateUniqueNumber($game->length);
        $game->save();
        return $game->id ;
    }
}
