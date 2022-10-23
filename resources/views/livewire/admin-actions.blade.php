<div class="content">
    <h3>{{ ucfirst($monthStatus->value) }}</h3>

    @switch ($monthStatus)
        @case (\App\Lib\MonthStatus::PLAYING)
            @livewire('admin.playing', array('latestMonth' => $latestMonth))
            @break
        @case (\App\Lib\MonthStatus::NOMINATING)
            @livewire('admin.nominating', array('latestMonth' => $latestMonth))
            @break
        @case (\App\Lib\MonthStatus::JURY)
            @livewire('admin.jury', array('latestMonth' => $latestMonth))
            @break
        @case (\App\Lib\MonthStatus::VOTING)
            @livewire('admin.voting', array('latestMonth' => $latestMonth))
            @breakbreak
        @default
            Nothing to do
    @endswitch
</div>
