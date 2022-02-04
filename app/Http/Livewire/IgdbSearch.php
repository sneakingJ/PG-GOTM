<?php

namespace App\Http\Livewire;

use Livewire\Component;
use MarcReichel\IGDBLaravel\Models\Game;
use Illuminate\Support\Str;

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
     * @var bool
     */
    public bool $noResults = false;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->noResults = false;
        $this->games = array();
        $gameList = array();

        if (Str::length($this->searchstring) > 3) {
            $results = Game::whereLike('name', $this->searchstring, false)->with(['cover'])->all();

            $results->each(function ($item, $key) use (&$gameList) {
                $game = array();
                $game['name'] = $item->name . ' (' . Str::substr($item->first_release_date, 0, 4) . ')';

                if (empty($item->cover)) {
                    $game['cover'] = '';
                } else {
                    $game['cover'] = $item->cover['url'];
                }

                $gameList[] = $game;
            });

            if (empty($gameList)) {
                $this->noResults = true;
            }

            usort($gameList, array($this, 'sortByRelevance'));
            $this->games = array_slice($gameList, 0, 10, true);
        }

        return view('livewire.igdb-search');
    }

    /**
     * @param array $x
     * @param array $y
     * @return int
     */
    private function sortByRelevance(array $x, array $y): int
    {
        $levX = levenshtein($this->searchstring, $x['name']);
        $levY = levenshtein($this->searchstring, $y['name']);

        return $levX > $levY ? 1 : -1;
    }
}
