<div class="row item-user-follow">
    <div class="col-sm-1 mark-dot" style="background-color: orange;"></div>
    <div class="col-md-1 hvr-bounce-to-right">
        <img class="user-ava" src="{{ $userFollow->avatar_link }}" alt="user-ava">
    </div>
    <div class="item-user-info">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('users.show', $userFollow->id) }}">
                    <h3 style="margin-top: 10px;">
                        {{ $userFollow->name }}
                    </h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
