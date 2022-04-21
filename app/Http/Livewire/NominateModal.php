<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;
use Illuminate\Support\Str;
use MarcReichel\IGDBLaravel\Models\Game;
use App\Models\Nomination;
use App\Models\Pitch;
use App\Models\Winner;

/**
 *
 */
class NominateModal extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['activateModal', 'disableModal'];

    /**
     * @var string[]
     */
    protected array $rules = [
        'gameId' => 'required'
    ];

    /**
     * @var int
     */
    public int $monthId = 0;

    /**
     * @var bool
     */
    public bool $active = false;

    /**
     * @var string
     */
    public string $gameId = '';

    /**
     * @var string
     */
    public string $gameName = '';

    /**
     * @var string
     */
    public string $gameCover = '';

    /**
     * @var string
     */
    public string $gamePitch = '';

    /**
     * @var bool
     */
    public bool $gameShort = true;



    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->monthId = Month::where('status', MonthStatus::NOMINATING)->first()->id;

        return view('components.nominate-modal');
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $cover
     * @return void
     */
    public function activateModal(string $id, string $name, string $cover)
    {
        if ($this->gameId !== $id) {
            $this->gamePitch = '';
        }
        $this->gameId = $id;
        $this->gameName = html_entity_decode($name);
        $this->gameCover = $cover;
        $this->active = true;
    }

    /**
     * @return void
     */
    public function disableModal()
    {
        $this->active = false;
    }

    /**
     * @return void
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function nominate()
    {
        $user = session('auth');

        $errorMessage = $this->verify($user['id']);
        if (!is_null($errorMessage)) {
            $this->disableModal();
            $this->emitTo('nominate-message', 'showError', $errorMessage);
            return;
        }

        $this->createNomination($user['id']);

        $this->disableModal();

        $this->emitTo('nominate-message', 'showSubmitted');
    }

    /**
     * @param string $userId
     * @return string|null
     */
    private function verify(string $userId): ?string
    {
        if (Nomination::where('month_id', $this->monthId)->where('discord_id', $userId)->where('short', $this->gameShort)->exists()) {
            $type = $this->gameShort ? 'short' : 'long';
            return 'You already nominated a ' . $type . ' game this month.';
        }

        if (Nomination::where('month_id', $this->monthId)->where('game_id', $this->gameId)->exists()) {
            return 'This game has already been nominated for this month.';
        }

        if (Winner::where('game_id', $this->gameId)->exists()) {
            return 'This game already was GOTM.';
        }

        return null;
    }

    /**
     * @param string $userId
     * @return void
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    private function createNomination(string $userId)
    {
        $game = Game::where('id', (int)$this->gameId)->with(['cover'])->first();

        $nomination = new Nomination();
        $nomination->month_id = $this->monthId;
        $nomination->discord_id = $userId;
        $nomination->game_id = $game->id;
        $nomination->game_name = $game->name;
        $nomination->game_year = Str::substr($game->first_release_date, 0, 4);
        $nomination->game_cover = empty($game->cover['url']) ? '' : $game->cover['url'];
        $nomination->game_url = $game->url;
        $nomination->game_platform_ids = is_array($game->platforms) ? implode(',', $game->platforms) : $game->platforms;
        $nomination->short = $this->gameShort;
        $nomination->save();

        if (!empty($this->gamePitch)) {
            $pitch = new Pitch();
            $pitch->nomination_id = $nomination->id;
            $pitch->discord_id = $userId;
            $pitch->pitch = Str::limit($this->gamePitch, 1000, ' (...)');
            $pitch->save();
        }
    }
}
