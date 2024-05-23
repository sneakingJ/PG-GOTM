<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Nomination;
use App\Models\Winner;
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
     * @var int
     */
    public int $monthId = 0;

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
        // Cache the month ID to avoid repeated queries
        $this->monthId = cache()->remember('nominating_month_id', 3600, function () {
            return Month::where('status', MonthStatus::NOMINATING)->first()->id;
        });

        $this->noResults = false;
        $this->games = array();

        $searchTerm = trim(Str::limit($this->searchName, 50));
        if (Str::length($searchTerm) >= 3) {
            $searchQuery = Game::search($searchTerm);

            if (!empty($this->searchYear) && strlen($this->searchYear) == 4) {
                $searchQuery->whereYear('first_release_date', trim($this->searchYear));
            }

            $gameList = $this->processResult($searchQuery->with(['cover'])->get());

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

            $game['alreadyNominated'] = Nomination::where('month_id', $this->monthId)->where('game_id', $item->id)->exists();
            $game['alreadyWon'] = Winner::where('game_id', $item->id)->exists();

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
