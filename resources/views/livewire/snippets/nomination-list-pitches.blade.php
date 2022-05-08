<div class="modal-pitches-list pb-2">
    @foreach($pitches as $pitch)
        <p class="mb-3">{!! nl2br($pitch->pitch) !!}</p>
    @endforeach
</div>
