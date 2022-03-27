<div class="block">
    @if($nominationExists)
        <livewire:nomination-list/>
    @elseif($votingExists)
        <livewire:voting-result/>
    @else
        Play the games!
    @endif
</div>
