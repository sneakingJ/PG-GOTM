<div class="block">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2">Past Months</h1>
        </div>
    </div>
    @empty($monthId)
        @foreach($months as $month)
            @if($month->id < 45)
                @break
            @endif
            <h3 class="title is-3"><a href="{{ route('history', ['monthId' => $month->id]) }}">{{ $month->year }} - {{ str_pad($month->month, 2, '0', STR_PAD_LEFT) }}</a></h3>
        @endforeach
    @else
        <livewire:voting-result :monthId="$monthId"/>
    @endempty
</div>
