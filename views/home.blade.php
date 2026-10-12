{{-- Nebula Limeris - Limeris.fr by Sliver91 --}}
@extends('layouts.base')

@section('title', trans('messages.home'))

@section('app')
    @php
        $nlVote = Route::has('vote.home') && class_exists(\Azuriom\Plugin\Vote\Models\Vote::class);
        $nlSep = app()->getLocale() === 'fr' ? ' ' : ',';
        $nlAddress = $server ? $server->fullAddress() : theme_config('address');
        $nlJoinUrl = $server ? $server->join_url : null;
        $nlVersion = theme_config('version');
        $nlSize = in_array(theme_config('title_size'), ['normal', 'grand', 'geant'], true) ? theme_config('title_size') : 'grand';
        $nlDiscord = theme_config('discord');
        $nlTitle = theme_config('title') ?: site_name();
        $nlTopCount = in_array((int) theme_config('top_count', 5), [3, 5, 10], true) ? (int) theme_config('top_count', 5) : 5;
        $nlShow = fn (string $key) => (bool) theme_config('show_'.$key, true);
        $nlExtra = theme_config('extra_label') && theme_config('extra_url');
        $nlVotesMonth = null;
        $nlTop = collect();
        $nlGoal = 0;

        if ($nlVote) {
            $nlVotesMonth = \Illuminate\Support\Facades\Cache::remember('nebula-limeris.votes.month', now()->addMinutes(5), function () {
                return \Azuriom\Plugin\Vote\Models\Vote::where('created_at', '>', now()->startOfMonth())->count();
            });
            $nlTop = \Azuriom\Plugin\Vote\Models\Vote::getTopVoters(now()->startOfMonth())->take($nlTopCount);
            $nlGoal = (int) setting('vote.goal.target', 0);
        }

        $nlFirst = $posts->first();
        $nlOthers = $posts->slice(1);

        $nlHasGoal = $nlVote && $nlGoal > 0 && $nlShow('goal');
        $nlHasTop = $nlVote && $nlShow('top');
        $nlHasServers = ! $servers->isEmpty() && $nlShow('servers');
        $nlHasAside = $nlHasGoal || $nlHasTop || $nlHasServers || $nlDiscord;
        $nlHasMain = $nlShow('news') || $message;
        $nlStatOnline = $server && $nlShow('online');
        $nlStatVotes = $nlVotesMonth !== null && $nlShow('votes');
    @endphp

    <section class="nl-hero">
        <div class="container">
            <h1 class="nl-title" data-nl-fit="{{ $nlSize }}"><span>{{ $nlTitle }}</span></h1>

            <div class="nl-hero-row">
                <div class="nl-hero-text">
                    @if(theme_config('tagline'))
                        <p class="nl-tagline">{{ theme_config('tagline') }}</p>
                    @endif

                    <div class="nl-actions">
                        @if($nlJoinUrl)
                            <a class="nl-btn" href="{{ $nlJoinUrl }}">{{ trans('theme::theme.hero.join') }}</a>
                        @elseif($nlAddress)
                            <button type="button" class="nl-btn" data-nl-copy="{{ $nlAddress }}" data-nl-copied="{{ trans('theme::theme.hero.copied') }}">{{ trans('theme::theme.hero.join') }}</button>
                        @endif

                        @if($nlVote && theme_config('show_vote_button', true))
                            <a class="nl-btn nl-btn-line" href="{{ route('vote.home') }}">{{ trans('theme::theme.hero.vote') }}</a>
                        @endif

                        @if($nlExtra)
                            <a class="nl-btn nl-btn-line" href="{{ theme_config('extra_url') }}">{{ theme_config('extra_label') }}</a>
                        @endif
                    </div>

                    @if($nlAddress)
                        <button type="button" class="nl-address" data-nl-copy="{{ $nlAddress }}" data-nl-copied="{{ trans('theme::theme.hero.copied') }}">{{ $nlAddress }}</button>
                    @endif
                </div>

                @if($nlStatOnline || $nlStatVotes || $nlVersion)
                    <dl class="nl-stats">
                        @if($nlStatOnline)
                            <div>
                                @if($server->isOnline())
                                    <dd>{{ number_format($server->getOnlinePlayers(), 0, ',', $nlSep) }}</dd>
                                    <dt><i class="nl-dot"></i>{{ trans('theme::theme.hero.online') }}</dt>
                                @else
                                    <dd>–</dd>
                                    <dt><i class="nl-dot nl-dot-off"></i>{{ trans('theme::theme.hero.offline') }}</dt>
                                @endif
                            </div>
                        @endif
                        @if($nlStatVotes)
                            <div>
                                <dd>{{ number_format($nlVotesMonth, 0, ',', $nlSep) }}</dd>
                                <dt>{{ trans('theme::theme.hero.votes') }}</dt>
                            </div>
                        @endif
                        @if($nlVersion)
                            <div>
                                <dd>{{ $nlVersion }}</dd>
                                <dt>{{ trans('theme::theme.hero.version') }}</dt>
                            </div>
                        @endif
                    </dl>
                @endif
            </div>
        </div>
    </section>

    <div class="container nl-alerts">
        @include('elements.session-alerts')
    </div>

    @if($nlHasMain || $nlHasAside)
    <main class="container nl-home @if(! $nlHasMain || ! $nlHasAside) nl-home-single @endif">
        @if($nlHasMain)
        <div class="nl-col">
            @if($message)
                <div class="nl-message">{{ $message }}</div>
            @endif

            @if($nlShow('news'))
            <div class="nl-head">
                <h2>{{ trans('theme::theme.news.title') }}</h2>
                @if(Route::has('posts.index') && ! $posts->isEmpty())
                    <a href="{{ route('posts.index') }}">{{ trans('theme::theme.news.all') }}</a>
                @endif
            </div>

            @if($nlFirst)
                <article class="nl-lead">
                    @if($nlFirst->hasImage())
                        <a href="{{ route('posts.show', $nlFirst) }}"><img src="{{ $nlFirst->imageUrl() }}" alt="{{ $nlFirst->title }}"></a>
                    @endif
                    <time datetime="{{ $nlFirst->published_at->toDateString() }}">{{ format_date($nlFirst->published_at) }} · {{ $nlFirst->author->name }}</time>
                    <h3><a href="{{ route('posts.show', $nlFirst) }}">{{ $nlFirst->title }}</a></h3>
                    <p>{{ Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('<', ' <', $nlFirst->content)))), 240) }}</p>
                </article>

                @if(! $nlOthers->isEmpty())
                    <ul class="nl-posts">
                        @foreach($nlOthers as $post)
                            <li>
                                <time datetime="{{ $post->published_at->toDateString() }}">{{ format_date($post->published_at) }}</time>
                                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <p class="nl-empty">{{ trans('theme::theme.news.empty') }}</p>
            @endif
            @endif
        </div>
        @endif

        @if($nlHasAside)
        <aside class="nl-col">
            @if($nlHasGoal)
                <section class="nl-block">
                    <h2>{{ trans('theme::theme.goal.title') }}</h2>
                    <div class="nl-bar" role="img" aria-label="{{ $nlVotesMonth }} / {{ $nlGoal }}"><b style="width: {{ min(100, round($nlVotesMonth / $nlGoal * 100)) }}%"></b></div>
                    <div class="nl-line">
                        <span>{{ trans('theme::theme.goal.count', ['count' => number_format($nlVotesMonth, 0, ',', $nlSep)]) }}</span>
                        <span>{{ trans('theme::theme.goal.target', ['target' => number_format($nlGoal, 0, ',', $nlSep)]) }}</span>
                    </div>
                </section>
            @endif

            @if($nlHasTop)
                <section class="nl-block">
                    <h2>{{ trans('theme::theme.top.title') }}</h2>
                    @if($nlTop->isEmpty())
                        <p class="nl-empty">{{ trans('theme::theme.top.empty') }}</p>
                    @else
                        <ol class="nl-top">
                            @foreach($nlTop as $nlEntry)
                                <li>
                                    <b>{{ $nlEntry->position }}</b>
                                    <span>{{ $nlEntry->user?->name ?? '?' }}</span>
                                    <span>{{ trans('theme::theme.top.votes', ['count' => number_format($nlEntry->votes, 0, ',', $nlSep)]) }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>
            @endif

            @if($nlHasServers)
                <section class="nl-block">
                    <h2>{{ trans('messages.servers') }}</h2>
                    <ul class="nl-servers">
                        @foreach($servers as $nlServer)
                            <li>
                                <span><i class="nl-dot @if(! $nlServer->isOnline()) nl-dot-off @endif"></i>{{ $nlServer->name }}</span>
                                <span>
                                    @if($nlServer->isOnline())
                                        {{ trans('theme::theme.servers.players', ['count' => $nlServer->getOnlinePlayers(), 'max' => $nlServer->getMaxPlayers()]) }}
                                    @else
                                        {{ trans('messages.server.offline') }}
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if($nlDiscord)
                <section class="nl-block">
                    <h2>{{ trans('theme::theme.discord.title') }}</h2>
                    <p class="nl-muted mb-0">{{ trans('theme::theme.discord.text') }}</p>
                    <div><a class="nl-btn nl-btn-line" href="{{ $nlDiscord }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-discord"></i> {{ trans('theme::theme.discord.join') }}</a></div>
                </section>
            @endif
        </aside>
        @endif
    </main>
    @endif

    @if(theme_config('show_steps', true) && $nlAddress)
        <section class="nl-join">
            <div class="container nl-join-in">
                <h2>{{ trans('theme::theme.steps.title') }}</h2>
                <ol class="nl-steps">
                    <li><strong>{{ trans('theme::theme.steps.one') }}</strong><span>{{ trans('theme::theme.steps.one_text') }} @if($nlVersion)({{ $nlVersion }})@endif</span></li>
                    <li><strong>{{ trans('theme::theme.steps.two') }}</strong><span>{{ trans('theme::theme.steps.two_text') }}</span></li>
                    <li><strong>{{ trans('theme::theme.steps.three') }}</strong><span>{{ trans('theme::theme.steps.three_text') }}</span>
                        <button type="button" class="nl-address" data-nl-copy="{{ $nlAddress }}" data-nl-copied="{{ trans('theme::theme.hero.copied') }}">{{ $nlAddress }}</button></li>
                </ol>
            </div>
        </section>
    @endif
@endsection
