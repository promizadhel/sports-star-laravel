<x-layout>
    <main>
        <h1 class="{{ $group }}">{{ $title }}</h1>
        <div class="card {{ $group }}">
            <img src="/images/teams/{{ $curr['current_team'] }}.png" alt="All blacks logo" class="logo" />
            <div class="name">
                <em>#<span id='number'>{{ $curr['number'] }}</span></em>
                <h2><span id='first-name'>{{ $curr['first_name'] }}</span><strong><span id='last-name'>{{ $curr['last_name'] }}</span></strong></h2>
            </div>
            <div class="profile">
                <img id='image' src="/images/players/{{ $curr['img_dir'] }}/{{ $curr['image'] }}" alt="{{ $curr['first_name'] }} {{ $curr['last_name'] }}" class="headshot" />
                <div class="features">
                    @foreach ($curr['featured'] as $statistic)
                        <div class="feature">
                            <h3>{{ $statistic['label'] }}</h3>
                            <span id='{{ Str::lower($statistic['label']) }}'>{{ $statistic['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bio">
                <div class="data">
                    <strong>Position</strong>
                    <span id='position'>{{ $curr['position'] }}</span>
                </div>
                <div class="data">
                    <strong>Weight</strong>
                    <span id='weight'>{{ $curr['weight'] }}</span>KG
                </div>
                <div class="data">
                    <strong>Height</strong>
                    <span id='height'>{{ $curr['height'] }}</span>
                </div>
                <div class="data">
                    <strong>Age</strong>
                    <span id='age'>{{ $curr['age'] }}</span> years
                </div>
            </div>
        </div>
        <div class="side-menu">
            <div class="menu prev {{ $group }}">
                <a onclick="loadPlayer({{ $prev['id'] }}, '{{ $group }}')" id='prev-name'>{{ $prev['name'] }}</a>
            </div>
            <div class="menu current">
                <a id='curr-name'>{{ $curr['name'] }}</a>
            </div>
            <div class="menu next {{ $group }}">
                <a onclick="loadPlayer({{ $next['id'] }}, '{{ $group }}')" id='next-name'>{{ $next['name'] }}</a>
            </div>
        </div>
    </main>
</x-layout>
<script>
    function loadPlayer(id, group){
        event.preventDefault();
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

        $.ajaxSetup({
            headers: {
                'X-CSF-TOKEN' : CSRF_TOKEN
            }
        });

        $.ajax({
            url: "/retrieve",
            type: "get",
            data: {
                id : id,
                group: group
            },
            success: function(response) {
                $('#number').html(response['curr']['number']);
                $('#first-name').html(response['curr']['first_name']);
                $('#last-name').html(response['curr']['last_name']);
                $('#points').html(response['curr']['points']);
                $('#games').html(response['curr']['games']);
                $('#tries').html(response['curr']['tries']);
                $('#position').html(response['curr']['position']);
                $('#weight').html(response['curr']['weight']);
                $('#height').html(response['curr']['height']);
                $('#age').html(response['curr']['age']);
                $('#image').attr('src', '/images/players/' + response['curr']['img_dir'] + '/' + response['curr']['image']);
                $('#image').attr('alt', "promise");

                $('#prev-name').html(response['prev']['name']);
                $('#prev-name').attr('onclick', 'loadPlayer(' + response['prev']['id'] + ', "' + response['endpoint'] + '")');
                $('#curr-name').html(response['curr']['name']);
                $('#next-name').html(response['next']['name']);
                $('#next-name').attr('onclick', 'loadPlayer(' + response['next']['id'] + ', "' + response['endpoint'] + '")');
            }
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>