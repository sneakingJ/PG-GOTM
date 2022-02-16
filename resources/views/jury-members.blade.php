<div class="box">
    <div class="content">
        <h1 class="title is-2">Jury Members</h1>
        <ul>
            @foreach($members as $member)
                <li>{{ $member->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
