{{-- Nebula Limeris - Limeris.fr by Sliver91 --}}
<style>
    body.nl[data-bs-theme=light] {
        --bs-primary: {{ $light }};
        --bs-primary-rgb: {{ color_rgb($light) }};
        --bs-primary-color-contrast: {{ color_contrast($light) }};
        --bs-primary-text-emphasis: {{ color_shade($light, 0.3) }};
        --bs-primary-bg-subtle: {{ color_tint($light, 0.85) }};
        --bs-primary-border-subtle: {{ color_tint($light, 0.6) }};
        --bs-link-color: {{ $light }};
        --bs-link-color-rgb: {{ color_rgb($light) }};
        --bs-link-hover-color: {{ color_shade($light, 0.25) }};
        --bs-link-hover-color-rgb: {{ color_rgb(color_shade($light, 0.25)) }};
        --bs-btn-primary-hover-bg: {{ color_mix(color_contrast($light), $light, 0.15) }};
        --bs-btn-primary-active-bg: {{ color_mix(color_contrast($light), $light, 0.15) }};
        --bs-btn-primary-focus-shadow-rgb: {{ color_rgb($light) }};
    }

    body.nl[data-bs-theme=dark] {
        --bs-primary: {{ $dark }};
        --bs-primary-rgb: {{ color_rgb($dark) }};
        --bs-primary-color-contrast: {{ color_contrast($dark) }};
        --bs-primary-text-emphasis: {{ color_tint($dark, 0.3) }};
        --bs-primary-bg-subtle: {{ color_shade($dark, 0.8) }};
        --bs-primary-border-subtle: {{ color_shade($dark, 0.5) }};
        --bs-link-color: {{ $dark }};
        --bs-link-color-rgb: {{ color_rgb($dark) }};
        --bs-link-hover-color: {{ color_tint($dark, 0.3) }};
        --bs-link-hover-color-rgb: {{ color_rgb(color_tint($dark, 0.3)) }};
        --bs-btn-primary-hover-bg: {{ color_mix(color_contrast($dark), $dark, 0.15) }};
        --bs-btn-primary-active-bg: {{ color_mix(color_contrast($dark), $dark, 0.15) }};
        --bs-btn-primary-focus-shadow-rgb: {{ color_rgb($dark) }};
    }
</style>
