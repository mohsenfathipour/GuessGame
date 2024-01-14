<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\GameStartRequest;
use App\Models\Game;
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
        return $game->id ;
    }

    /*
     * try new number
     */
    public function try($game , $num)
    {
        $game = Game::find($game);

        if(!$game)
            return 'Game not found!';

        if($game->status_enum == 'done')
            return 'Game is finished!';

        if($game->length != strlen($num))
            return 'Number length should be ' . $game->length;

        $corrects = 0;
        $misplaces = 0;

        $game_nums  = str_split($game->number);
        $try_nums  = str_split($num);

        if($try_nums != array_unique($try_nums) )
            return 'Numbers can not be duplicated!';


        # increase try;
        $game->try++;
        $game->save();

        # corrects:
        foreach ($game_nums as $i => $game_num)
            if($try_nums[$i] == $game_num)
                $corrects++;

        # win? :
        if($corrects == $game->length){
            $game->status_enum = 'done';
            $game->end_at = date('Y-m-d H:i:s');
            $game->save();
            return 'You win! the number was ' . $game->number . ' try: ' . $game->try;
        }

        # misplaces
        foreach ($try_nums as $i => $try_num){
            if(in_array($try_num,$game_nums)
                && $game_nums[$i] != $try_num)
                $misplaces++;
        }

        return ['corrects' => $corrects,'misplaces' => $misplaces];
    }
}
