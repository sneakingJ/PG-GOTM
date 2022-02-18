<?php

namespace App\Http\Livewire;

use Livewire\Component;
use MarcReichel\IGDBLaravel\Models\Game;
use Illuminate\Support\Str;
use App\Models\IgdbPlatform;

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
     * @throws \JsonException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws \ReflectionException
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->noResults = false;
        $this->games = array();

        $searchTerm = trim(Str::limit($this->searchName, 50));
        if (Str::length($searchTerm) >= 3) {
            $searchQuery = Game::where(function ($query) use ($searchTerm) {
                $query->whereLike('name', $searchTerm, false)->orWhereLike('alternative_names.name', $searchTerm, false);
            });

            if (!empty($this->searchYear) && strlen($this->searchYear) == 4) {
                $searchQuery->whereYear('first_release_date', trim($this->searchYear));
            }

            $gameList = $this->processResult($searchQuery->with(['cover'])->all());

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
     * @param \Illuminate\Support\Collection $igdbResults
     * @return array
     */
    private function processResult(\Illuminate\Support\Collection $igdbResults): array
    {
        $gameList = array();

        $igdbResults->each(function ($item, $key) use (&$gameList) {
            $game = array();
            $game['name'] = $item->name;
            $game['year'] = Str::substr($item->first_release_date, 0, 4);
            $game['id'] = $item->id;
            $game['url'] = $item->url;
            $game['cover'] = empty($item->cover) ? '' : $item->cover['url'];

            $platforms = empty($item->platforms) ? array() : $item->platforms;
            $game['platforms'] = IgdbPlatform::whereIn('igdb_id', $platforms)->whereNotNull('logo')->get();

            $gameList[] = $game;
        });

        if (empty($gameList)) {
            $this->noResults = true;
        }

        usort($gameList, array($this, 'sortByRelevance'));

        return $gameList;
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
