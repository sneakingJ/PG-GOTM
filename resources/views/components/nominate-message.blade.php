<div>
    @if($submitted)
        <article class="message is-success mb-5">
            <div class="message-header">
                <p>Success</p>
            </div>
            <div class="message-body">
                Game nomination submitted!
            </div>
        </article>
    @elseif(!empty($errorMessage))
        <article class="message is-warning mb-5">
            <div class="message-header">
                <p>Error</p>
            </div>
            <div class="message-body">
                {{ $errorMessage }}
            </div>
        </article>
    @else
        <article class="message is-info mb-5">
            <div class="message-header">
                <p>Info</p>
            </div>
            <div class="message-body">
                Search for the game title and the year it was first released. The year field is optional but the title input needs to be at least 3 characters long before the search starts.
            </div>
        </article>
    @endif
</div>
