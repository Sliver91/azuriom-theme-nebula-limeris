{{-- Nebula Limeris - Limeris.fr by Sliver91 --}}
@php
    $nlFooterLinks = collect(theme_config('footer_links', []) ?: [])->filter(fn ($link) => ! empty($link['name']) && ! empty($link['value']));
@endphp
<div class="container nl-footer-in">
    <div class="nl-footer-main">
        <div class="nl-brand nl-footer-brand">{{ site_name() }}</div>
        @if(theme_config('footer_text'))
            <p class="nl-footer-text">{{ theme_config('footer_text') }}</p>
        @endif
        <p class="mb-0">{{ str_replace('{year}', date('Y'), setting('copyright')) }} | @lang('messages.copyright')</p>
    </div>

    @if(! $nlFooterLinks->isEmpty())
        <nav class="nl-footer-nav">
            @foreach($nlFooterLinks as $nlLink)
                <a href="{{ $nlLink['value'] }}" target="_blank" rel="noopener noreferrer">{{ $nlLink['name'] }}</a>
            @endforeach
        </nav>
    @endif

    <div class="nl-footer-side">
        @if(theme_config('show_social', true))
            <div class="nl-footer-links">
                @foreach(social_links() as $link)
                    <a href="{{ $link->value }}" title="{{ $link->title }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link->title }}">
                        <i class="{{ $link->icon }}"></i>
                    </a>
                @endforeach
            </div>
        @endif

        <p class="nl-credit mb-0">
            {{ trans('theme::theme.footer.credit') }}
            <a href="https://limeris.fr" target="_blank" rel="noopener noreferrer">Limeris.fr by Sliver91</a>
            <a href="https://discord.gg/4kV8pgC3Ck" target="_blank" rel="noopener noreferrer" aria-label="Discord"><i class="bi bi-discord"></i></a>
        </p>
    </div>
</div>
