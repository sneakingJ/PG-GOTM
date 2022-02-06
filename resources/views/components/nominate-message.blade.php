<div>
    @if($submitted)
        <article class="message is-success mt-5">
            <div class="message-header">
                <p>Success</p>
            </div>
            <div class="message-body">
                Game nomination submitted!
            </div>
        </article>
    @endif

    @if(!empty($errorMessage))
        <article class="message is-warning mt-5">
            <div class="message-header">
                <p>Error</p>
            </div>
            <div class="message-body">
                {{ $errorMessage }}
            </div>
        </article>
    @endif
</div>
