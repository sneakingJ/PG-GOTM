<div>
    <article class="message is-success is-pulled-right @if($voted) is-success @else is-danger @endif">
        <div class="message-body p-1">
            <p>@if($voted) Voted @else Not yet voted @endif</p>
        </div>
    </article>
</div>
