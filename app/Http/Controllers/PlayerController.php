<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon; 

class PlayerController extends Controller
{
    private $title = ['allblacks' => 'ALL BLACKS RUGBY', 'nba.players' => 'NBA BASKETBALL'];
    private $group = ['allblacks' => 'allblacks', 'nba.players' => 'nba'];
    /**
     * Show a player profile
     *
     * @param  int $id
     * @param  string $endpoint
     * @return \Illuminate\View\View
     */
    // public function show($endpoint = 'allblacks', $id = 1)
    public function show(Request $request)
    {
        $uri = explode("/", \Request::getRequestUri());
        array_shift($uri);

        $is_retrieve = strpos($uri[0], 'retrieve');

        if($is_retrieve === false){
            $endpoint = $uri[0];
            $id = (int) $uri[1];
        }
        else{
            $endpoint = $request->input('group');
            $id = (int) $request->input('id');
        }
        
        $endpoint = $endpoint == "nba" ? $endpoint.".players" : $endpoint;

        $allPlayers = $this->allPlayer($endpoint);

        $player = $this->multiple( $allPlayers->get($id), $endpoint);

        $prev_player = $allPlayers->has($id - 1) ? $this->multiple($allPlayers->get($id - 1), $endpoint) : $this->multiple( $allPlayers->last(), $endpoint);
        $next_player = $allPlayers->has($id + 1) ? $this->multiple( $allPlayers->get($id + 1), $endpoint) : $this->multiple( $allPlayers->get(1), $endpoint);

        if($is_retrieve === false)
        {
            $page_title = $this->title[$endpoint];

            return view('player', 
                array(
                    'prev' => $prev_player, 
                    'curr' => $player, 
                    'next' => $next_player, 
                    'endpoint' => $endpoint, 
                    'title' => $page_title,
                    'group' => $this->group[$endpoint]
                )
            );
        }
    
        return array('prev' => $prev_player, 'curr' => $player, 'next' => $next_player, 'endpoint' => $endpoint, 'title' => $endpoint);
    }

    /**
     * Retrieve and format multiple player profile
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    protected function multiple(Collection $player, string $endpoint)
    {
        $name = "";
        if($endpoint === "allblacks")
        {
            $name = $player->get('name');
            // split first & last name
            $names = collect(preg_split('/\s+/', $player->get('name')));
            $player->put('id', $player->get('id'));
            $player->put('last_name', $names->pop());
            $player->put('first_name', $names->join(' '));
            $player->put('height', $player->get('height'). " CM");
            $player->put('img_dir', 'allblacks');
            $player->put('current_team', 'allblacks');

            // stats to feature
            $player->put('featured', $this->feature($player));
        }
        else
        {   
            $name = $player->get('first_name') . " " .$player->get('last_name');
            $player->put('name', $name);
            $player->put('height', $player->get('feet') . "'" .$player->get('inches') . "\"");

            $age = Carbon::parse($player->get('birthday'))->diff(Carbon::now())->y;
            $player->put('age', $age);
            $player->put('img_dir', 'nba');

            // stats to feature
            $player->put('featured', $this->stats($player->get('id')));
            
        }

        // determine the image filename from the name
        $player->put('image', $this->image($name));

        return $player;
    }

    /**
     * Retrieve player data from the API
     *
     * @param int $id
     * @param string $endpoint
     * @return \Illuminate\Support\Collection
     */
    protected function player(int $id, string $endpoint): Collection
    {
        $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint';        

        $json = Http::get("$baseEndpoint/$endpoint/id/$id", [
            'API_KEY' => config('api.key'),
        ])->json();

        return collect(array_shift($json));
    }

    /**
     * Retrieve player data from the API
     *
     * @param string $endpoint
     * @return \Illuminate\Support\Collection
     */
    protected function allPlayer(string $endpoint): Collection
    {
        $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint';

        $json = collect();
        $allJson = Http::get("$baseEndpoint/$endpoint", [
            'API_KEY' => config('api.key'),
        ])->json();

        foreach($allJson as $each)
        {
            $json->put('id', $each['id']);
            $json->put($each['id'], collect($each));
        }        

        return $json;
    }

    /**
     * Determine the image for the player based off their name
     *
     * @param string $name
     * @return string filename
     */
    protected function image(string $name): string
    {
        return preg_replace('/\W+/', '-', strtolower($name)) . '.png';
    }

    /**
     * Build stats to feature for this player
     *
     * @param \Illuminate\Support\Collection $player
     * @return \Illuminate\Support\Collection features
     */
    protected function feature(Collection $player): Collection
    {
        return collect([
            ['label' => 'Points', 'value' => $player->get('points')],
            ['label' => 'Games', 'value' => $player->get('games')],
            ['label' => 'Tries', 'value' => $player->get('tries')],
        ]);
    }

    /**
     * Build and get stats to feature for this player
     *
     * @param \Illuminate\Support\Collection $player
     * @return \Illuminate\Support\Collection features
     */
    protected function stats(int $id): Collection
    {
    
        $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nbs.stats';

        $json = Http::get("$baseEndpoint/$id", [
            'API_KEY' => config('api.key'),
        ])->json();

        $json = collect([
            "games" => 647,
            "assists" => 1812,
            "points" => 13199,
            "player_id" => 1,
            "rebounds" => 2523,
            "id" => 18
        ]);

        return collect([
            ['label' => 'Assists Per Game', 'value' => number_format((int) $json->get('assists') / (int) $json->get('games'), 2)],
            ['label' => 'Points Per Game', 'value' => number_format((int) $json->get('points') / (int) $json->get('games'), 2)],
            ['label' => 'Rebounds Per Game', 'value' => number_format((int) $json->get('rebounds') / (int) $json->get('games'), 2)],
        ]);
    }
}
