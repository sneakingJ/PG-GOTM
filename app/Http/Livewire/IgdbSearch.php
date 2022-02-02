<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;

/**
 *
 */
class IgdbSearch extends Component
{
    /**
     * @var string
     */
    public string $searchstring = "";

    /**
     * @var array
     */
    public array $games = array();

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->games = array();
        $gameList = array();

        if (Str::length($this->searchstring) > 3) {
            $results = Game::whereLike('name', $this->searchstring, false)->with(['cover'])->all();

            $results->each(function ($item, $key) use (&$gameList) {
                $game = array();
                $game['name'] = $item->name;

                if (empty($item->cover)) {
                    $game['cover'] = "";
                } else {
                    $game['cover'] = $item->cover['url'];
                }

                $gameList[] = $game;
            });

            usort($gameList, array($this, "sortByRelevance"));
            $this->games = array_slice($gameList, 0, 10, true);
        }

        return view('livewire.igdb-search');
    }

    /**
     * @param $x
     * @param $y
     * @return int
     */
    private function sortByRelevance($x, $y): int
    {
        $levX = levenshtein($this->searchstring, $x['name']);
        $levY = levenshtein($this->searchstring, $y['name']);

        return $levX > $levY ? 1 : -1;
    }
}
