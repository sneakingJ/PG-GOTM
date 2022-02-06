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
    public string $searchName = "";

    /**
     * @var string
     */
    public string $searchYear = "";

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
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException|\JsonException
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->noResults = false;
        $this->games = array();
        $gameList = array();

        if (Str::length($this->searchName) >= 3) {
            $searchQuery = Game::whereLike('name', trim($this->searchName), false);
            if (!empty($this->searchYear)) {
                $searchQuery->whereLike('release_dates.human', trim($this->searchYear), false);
            }
            $results = $searchQuery->with(['cover'])->all();

            $results->each(function ($item, $key) use (&$gameList) {
                $game = array();
                $game['name'] = $item->name;
                $game['year'] = Str::substr($item->first_release_date, 0, 4);
                $game['id'] = $item->id;
                $game['url'] = $item->url;
                empty($item->cover) ? $game['cover'] = '' : $game['cover'] = $item->cover['url'];

                $gameList[] = $game;
            });

            if (empty($gameList)) {
                $this->noResults = true;
            }

            usort($gameList, array($this, 'sortByRelevance'));
            $this->games = array_slice($gameList, 0, 20, true);
        }

        return view('livewire.igdb-search');
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->emitTo('nominate-message', 'removeAll');
    }

    /**
     * @param array $x
     * @param array $y
     * @return int
     */
    private function sortByRelevance(array $x, array $y): int
    {
        $levX = levenshtein($this->searchName, $x['name']);
        $levY = levenshtein($this->searchName, $y['name']);

        return $levX > $levY ? 1 : -1;
    }
}
