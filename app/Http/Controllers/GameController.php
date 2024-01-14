<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\GameStartRequest;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Game
 */
class GameController extends Controller
{
    /*
     * start the game
     */
    public function start(GameStartRequest $request)
    {
        $game = new Game();
        $game->length = $request->length ?? 3;
        $game->number = generateUniqueNumber($game->length);
        $game->save();
        return response()->json([
            'success' => true,
            'data' => ['game_id' => $game->id],
        ], Response::HTTP_OK);
    }

    /*
     * try new number
     */
    public function try($game, $num)
    {
        $game = Game::find($game);

        if (!$game)
            return response()->json([
                'success' => false,
                'message' => 'Game not found!',
                'data' => null,
            ], Response::HTTP_NOT_FOUND);

        if ($game->status_enum == 'done')
            return response()->json([
                'success' => false,
                'message' => 'Game is finished!',
                'data' => null,
            ], Response::HTTP_REQUEST_TIMEOUT);

        if ($game->length != strlen($num))
            return response()->json([
                'success' => false,
                'message' => 'Number length should be ' . $game->length,
                'data' => null,
            ], Response::HTTP_NOT_ACCEPTABLE);

        $corrects = 0;
        $misplaces = 0;

        $game_nums = str_split($game->number);
        $try_nums = str_split($num);

        if ($try_nums != array_unique($try_nums))
            return response()->json([
                'success' => false,
                'message' => 'Numbers can not be duplicated!',
                'data' => null,
            ], Response::HTTP_NOT_ACCEPTABLE);

        # increase try;
        $game->try++;
        $game->save();

        # corrects:
        foreach ($game_nums as $i => $game_num)
            if ($try_nums[$i] == $game_num)
                $corrects++;

        # win? :
        if ($corrects == $game->length) {
            $game->status_enum = 'done';
            $game->end_at = date('Y-m-d H:i:s');
            $game->save();

            return response()->json([
                'success' => true,
                'message' => 'You win!',
                'data' => [
                    'number' => $game->number,
                    'try'   => $game->try
                ],
            ], Response::HTTP_OK);
        }

        # misplaces
        foreach ($try_nums as $i => $try_num) {
            if (in_array($try_num, $game_nums)
                && $game_nums[$i] != $try_num)
                $misplaces++;
        }

        return response()->json([
            'success' => false,
            'message' => 'Wrong!',
            'data' => [
                'num' => $num,
                'try'   => $game->try,
                'corrects' => $corrects,
                'misplaces' => $misplaces
            ],
        ], Response::HTTP_BAD_REQUEST);
    }
}
