{{-- Nebula Limeris - Limeris.fr by Sliver91 --}}
@extends('admin.layouts.admin')

@section('title', 'Nebula Limeris')

@include('admin.elements.color-picker')

@push('styles')
    <link href="{{ theme_asset('css/fonts.css') }}" rel="stylesheet">
    <style>
        .nl-cfg-card { display: block; width: 100%; padding: .6rem; text-align: center; }
        .nl-cfg-thumb { display: block; width: 100%; aspect-ratio: 8 / 5; object-fit: cover; border-radius: .25rem; }
        .nl-cfg-sample { display: grid; place-items: center; aspect-ratio: 8 / 3; font-size: 1.9rem; line-height: 1; text-transform: uppercase; }
        .nl-cfg-shape { display: block; width: 62%; aspect-ratio: 8 / 3; margin: .5rem auto; border: 2px solid currentColor; }
        .nl-cfg-veil { display: block; aspect-ratio: 8 / 3; border-radius: .25rem; background: url('{{ theme_asset('img/ciel-bleu-mini.webp') }}') center / cover; position: relative; overflow: hidden; }
        .nl-cfg-veil::after { content: ""; position: absolute; inset: 18% 12%; border-radius: .25rem; border: 1px solid rgba(255, 255, 255, .25); background: rgba(6, 11, 30, var(--nl-cfg-a)); }
    </style>
@endpush

@push('footer-scripts')
    <script>
        (function () {
            const list = document.getElementById('nlLinks');
            const template = document.getElementById('nlLinkTemplate');

            function bind(row) {
                row.querySelector('.nl-link-remove').addEventListener('click', function () { row.remove(); });
            }

            list.querySelectorAll('.nl-link-row').forEach(bind);

            document.getElementById('nlLinkAdd').addEventListener('click', function () {
                const row = template.content.firstElementChild.cloneNode(true);
                bind(row);
                list.appendChild(row);
            });

            document.getElementById('nlConfigForm').addEventListener('submit', function () {
                list.querySelectorAll('.nl-link-row').forEach(function (row, index) {
                    row.querySelectorAll('input').forEach(function (input) {
                        input.name = input.name.replace(/footer_links\[[^\]]*\]/, 'footer_links[' + index + ']');
                    });
                });
            });
        })();
    </script>
@endpush

@section('content')
    @php
        $nlCfg = function (string $key, $default = null) {
            return old($key, theme_config($key, $default));
        };
        $nlLinks = old('footer_links', theme_config('footer_links', [])) ?: [];
    @endphp

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $nlError)
                    <li>{{ $nlError }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.themes.config', $theme) }}" method="POST" id="nlConfigForm">
        @csrf

        {{-- Apparence --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-palette"></i> {{ trans('theme::theme.config.look') }}</h5></div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label d-block">{{ trans('theme::theme.config.background') }}</label>
                    <div class="row g-3">
                        @foreach(['violet', 'bleu', 'emeraude', 'braise', 'aube', 'etoiles', 'none'] as $nlOption)
                            <div class="col-6 col-md-4 col-xl-3">
                                <input type="radio" class="btn-check" name="background" id="background{{ $nlOption }}" value="{{ $nlOption }}" @checked($nlCfg('background', 'bleu') === $nlOption)>
                                <label class="btn btn-outline-primary nl-cfg-card" for="background{{ $nlOption }}">
                                    @if($nlOption === 'none')
                                        <span class="nl-cfg-thumb bg-body-secondary"></span>
                                    @else
                                        <img src="{{ theme_asset('img/ciel-'.$nlOption.'-mini.webp') }}" alt="" class="nl-cfg-thumb">
                                    @endif
                                    <span class="d-block mt-2">{{ trans('theme::theme.config.bg_'.$nlOption) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text">{{ trans('theme::theme.config.background_help') }}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">{{ trans('theme::theme.config.veil') }}</label>
                    <div class="row g-3">
                        @foreach(['leger' => '.4', 'moyen' => '.64', 'fort' => '.86'] as $nlOption => $nlAlpha)
                            <div class="col-4 col-xl-3">
                                <input type="radio" class="btn-check" name="veil" id="veil{{ $nlOption }}" value="{{ $nlOption }}" @checked($nlCfg('veil', 'moyen') === $nlOption)>
                                <label class="btn btn-outline-primary nl-cfg-card" for="veil{{ $nlOption }}">
                                    <span class="nl-cfg-veil" style="--nl-cfg-a: {{ $nlAlpha }}"></span>
                                    <span class="d-block mt-2">{{ trans('theme::theme.config.veil_'.$nlOption) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">{{ trans('theme::theme.config.radius') }}</label>
                    <div class="row g-3">
                        @foreach(['droit' => '0', 'leger' => '.4rem', 'rond' => '1.1rem'] as $nlOption => $nlRadius)
                            <div class="col-4 col-xl-3">
                                <input type="radio" class="btn-check" name="radius" id="radius{{ $nlOption }}" value="{{ $nlOption }}" @checked($nlCfg('radius', 'leger') === $nlOption)>
                                <label class="btn btn-outline-primary nl-cfg-card" for="radius{{ $nlOption }}">
                                    <span class="nl-cfg-shape" style="border-radius: {{ $nlRadius }}"></span>
                                    <span class="d-block mt-2">{{ trans('theme::theme.config.radius_'.$nlOption) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">{{ trans('theme::theme.config.title_font') }}</label>
                    <div class="row g-3">
                        @foreach(['condense' => ['Big Shoulders Display', 800, '2.3rem'], 'large' => ['Unbounded', 700, '1.25rem'], 'technique' => ['Chakra Petch', 700, '1.75rem']] as $nlOption => $nlFont)
                            <div class="col-4 col-xl-3">
                                <input type="radio" class="btn-check" name="title_font" id="font{{ $nlOption }}" value="{{ $nlOption }}" @checked($nlCfg('title_font', 'condense') === $nlOption)>
                                <label class="btn btn-outline-primary nl-cfg-card" for="font{{ $nlOption }}">
                                    <span class="nl-cfg-sample" style="font-family: '{{ $nlFont[0] }}', sans-serif; font-weight: {{ $nlFont[1] }}; font-size: {{ $nlFont[2] }}">{{ Str::limit(site_name(), 12, '') }}</span>
                                    <span class="d-block mt-2">{{ trans('theme::theme.config.font_'.$nlOption) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="colorDarkInput">{{ trans('theme::theme.config.color_dark') }}</label>
                        <input type="color" class="form-control form-control-color color-picker @error('color_dark') is-invalid @enderror" id="colorDarkInput" name="color_dark" value="{{ $nlCfg('color_dark', '#7af0c4') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="colorLightInput">{{ trans('theme::theme.config.color_light') }}</label>
                        <input type="color" class="form-control form-control-color color-picker @error('color_light') is-invalid @enderror" id="colorLightInput" name="color_light" value="{{ $nlCfg('color_light', '#0c7458') }}" required>
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input type="hidden" name="default_dark" value="0">
                    <input type="checkbox" class="form-check-input" id="default_darkSwitch" name="default_dark" value="1" @checked($nlCfg('default_dark', '1'))>
                    <label class="form-check-label" for="default_darkSwitch">{{ trans('theme::theme.config.default_dark') }}</label>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-menu-button-wide"></i> {{ trans('theme::theme.config.nav') }}</h5></div>
            <div class="card-body">
                @foreach(['nav_logo' => '0', 'nav_sticky' => '0', 'theme_switch' => '1'] as $nlKey => $nlDefault)
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="{{ $nlKey }}" value="0">
                        <input type="checkbox" class="form-check-input" id="{{ $nlKey }}Switch" name="{{ $nlKey }}" value="1" @checked($nlCfg($nlKey, $nlDefault))>
                        <label class="form-check-label" for="{{ $nlKey }}Switch">{{ trans('theme::theme.config.'.$nlKey) }}</label>
                    </div>
                @endforeach
                <div class="form-text">{{ trans('theme::theme.config.nav_logo_help') }}</div>
            </div>
        </div>

        {{-- Haut de l'accueil --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-type-h1"></i> {{ trans('theme::theme.config.home') }}</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="titleInput">{{ trans('theme::theme.config.title') }}</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="titleInput" name="title" maxlength="40" placeholder="{{ site_name() }}" value="{{ $nlCfg('title') }}">
                        <div class="form-text">{{ trans('theme::theme.config.title_help') }}</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label d-block">{{ trans('theme::theme.config.title_size') }}</label>
                        <div class="btn-group" role="group">
                            @foreach(['normal', 'grand', 'geant'] as $nlOption)
                                <input type="radio" class="btn-check" name="title_size" id="size{{ $nlOption }}" value="{{ $nlOption }}" @checked($nlCfg('title_size', 'grand') === $nlOption)>
                                <label class="btn btn-outline-primary" for="size{{ $nlOption }}">{{ trans('theme::theme.config.size_'.$nlOption) }}</label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="taglineInput">{{ trans('theme::theme.config.tagline') }}</label>
                    <input type="text" class="form-control @error('tagline') is-invalid @enderror" id="taglineInput" name="tagline" maxlength="200" value="{{ $nlCfg('tagline') }}">
                    <div class="form-text">{{ trans('theme::theme.config.tagline_help') }}</div>
                </div>

                <div class="row g-3">
                    <div class="mb-3 col-md-8">
                        <label class="form-label" for="addressInput">{{ trans('theme::theme.config.address') }}</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="addressInput" name="address" maxlength="100" placeholder="play.example.com" value="{{ $nlCfg('address') }}">
                        <div class="form-text">{{ trans('theme::theme.config.address_help') }}</div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="versionInput">{{ trans('theme::theme.config.version') }}</label>
                        <input type="text" class="form-control @error('version') is-invalid @enderror" id="versionInput" name="version" maxlength="20" placeholder="1.21" value="{{ $nlCfg('version') }}">
                        <div class="form-text">{{ trans('theme::theme.config.version_help') }}</div>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="show_vote_button" value="0">
                    <input type="checkbox" class="form-check-input" id="show_vote_buttonSwitch" name="show_vote_button" value="1" @checked($nlCfg('show_vote_button', '1'))>
                    <label class="form-check-label" for="show_vote_buttonSwitch">{{ trans('theme::theme.config.show_vote_button') }}</label>
                </div>

                <label class="form-label d-block">{{ trans('theme::theme.config.extra_button') }}</label>
                <div class="row g-3">
                    <div class="mb-1 col-md-4">
                        <input type="text" class="form-control @error('extra_label') is-invalid @enderror" name="extra_label" maxlength="40" placeholder="{{ trans('theme::theme.config.extra_label') }}" aria-label="{{ trans('theme::theme.config.extra_label') }}" value="{{ $nlCfg('extra_label') }}">
                    </div>
                    <div class="mb-1 col-md-8">
                        <input type="url" class="form-control @error('extra_url') is-invalid @enderror" name="extra_url" maxlength="200" placeholder="{{ trans('theme::theme.config.extra_url') }}" aria-label="{{ trans('theme::theme.config.extra_url') }}" value="{{ $nlCfg('extra_url') }}">
                    </div>
                </div>
                <div class="form-text mb-3">{{ trans('theme::theme.config.extra_help') }}</div>

                <label class="form-label d-block">{{ trans('theme::theme.config.stats') }}</label>
                @foreach(['show_online', 'show_votes'] as $nlKey)
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="{{ $nlKey }}" value="0">
                        <input type="checkbox" class="form-check-input" id="{{ $nlKey }}Switch" name="{{ $nlKey }}" value="1" @checked($nlCfg($nlKey, '1'))>
                        <label class="form-check-label" for="{{ $nlKey }}Switch">{{ trans('theme::theme.config.'.$nlKey) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sections de l'accueil --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-layout-text-window-reverse"></i> {{ trans('theme::theme.config.sections') }}</h5></div>
            <div class="card-body">
                <div class="row">
                    @foreach(['show_news', 'show_goal', 'show_top', 'show_servers', 'show_steps'] as $nlKey)
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-2">
                                <input type="hidden" name="{{ $nlKey }}" value="0">
                                <input type="checkbox" class="form-check-input" id="{{ $nlKey }}Switch" name="{{ $nlKey }}" value="1" @checked($nlCfg($nlKey, '1'))>
                                <label class="form-check-label" for="{{ $nlKey }}Switch">{{ trans('theme::theme.config.'.$nlKey) }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form-text mb-3">{{ trans('theme::theme.config.vote_help') }}</div>

                <div class="mb-3">
                    <label class="form-label d-block">{{ trans('theme::theme.config.top_count') }}</label>
                    <div class="btn-group" role="group">
                        @foreach(['3', '5', '10'] as $nlOption)
                            <input type="radio" class="btn-check" name="top_count" id="top{{ $nlOption }}" value="{{ $nlOption }}" @checked((string) $nlCfg('top_count', '5') === $nlOption)>
                            <label class="btn btn-outline-primary" for="top{{ $nlOption }}">{{ $nlOption }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label" for="discordInput">{{ trans('theme::theme.config.discord') }}</label>
                    <input type="url" class="form-control @error('discord') is-invalid @enderror" id="discordInput" name="discord" maxlength="200" placeholder="https://discord.gg/..." value="{{ $nlCfg('discord') }}">
                    <div class="form-text">{{ trans('theme::theme.config.discord_help') }}</div>
                </div>
            </div>
        </div>

        {{-- Pied de page --}}
        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="card-title mb-0"><i class="bi bi-layout-text-sidebar-reverse"></i> {{ trans('theme::theme.config.footer') }}</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="footerTextInput">{{ trans('theme::theme.config.footer_text') }}</label>
                    <textarea class="form-control @error('footer_text') is-invalid @enderror" id="footerTextInput" name="footer_text" maxlength="300" rows="2">{{ $nlCfg('footer_text') }}</textarea>
                    <div class="form-text">{{ trans('theme::theme.config.footer_text_help') }}</div>
                </div>

                <div class="form-check form-switch mb-1">
                    <input type="hidden" name="show_social" value="0">
                    <input type="checkbox" class="form-check-input" id="show_socialSwitch" name="show_social" value="1" @checked($nlCfg('show_social', '1'))>
                    <label class="form-check-label" for="show_socialSwitch">{{ trans('theme::theme.config.show_social') }}</label>
                </div>
                <div class="form-text mb-3">{{ trans('theme::theme.config.show_social_help') }}</div>

                <label class="form-label d-block">{{ trans('theme::theme.config.footer_links') }}</label>
                <div id="nlLinks">
                    @foreach($nlLinks as $nlIndex => $nlLink)
                        <div class="row g-2 mb-2 nl-link-row">
                            <div class="col-md-4"><input type="text" class="form-control" name="footer_links[{{ $nlIndex }}][name]" maxlength="40" placeholder="{{ trans('theme::theme.config.link_name') }}" value="{{ $nlLink['name'] ?? '' }}" required></div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="url" class="form-control" name="footer_links[{{ $nlIndex }}][value]" maxlength="200" placeholder="{{ trans('theme::theme.config.link_url') }}" value="{{ $nlLink['value'] ?? '' }}" required>
                                    <button class="btn btn-outline-danger nl-link-remove" type="button" aria-label="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <template id="nlLinkTemplate">
                    <div class="row g-2 mb-2 nl-link-row">
                        <div class="col-md-4"><input type="text" class="form-control" name="footer_links[new][name]" maxlength="40" placeholder="{{ trans('theme::theme.config.link_name') }}" required></div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="url" class="form-control" name="footer_links[new][value]" maxlength="200" placeholder="{{ trans('theme::theme.config.link_url') }}" required>
                                <button class="btn btn-outline-danger nl-link-remove" type="button" aria-label="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </template>
                <button type="button" class="btn btn-sm btn-success" id="nlLinkAdd"><i class="bi bi-plus-lg"></i> {{ trans('theme::theme.config.link_add') }}</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>

    <p class="text-body-secondary small mt-4 mb-0">
        <img src="{{ theme_asset('img/limeris.png') }}" alt="" width="18" height="18" class="me-1" style="width: 1.15rem; height: 1.15rem; vertical-align: -.2rem;">
        Nebula Limeris · <a href="https://limeris.fr" target="_blank" rel="noopener noreferrer">Limeris.fr by Sliver91</a> ·
        <a href="https://discord.gg/4kV8pgC3Ck" target="_blank" rel="noopener noreferrer"><i class="bi bi-discord"></i> Discord</a>
    </p>
@endsection
