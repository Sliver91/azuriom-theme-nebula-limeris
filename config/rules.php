<?php

// Nebula Limeris - Limeris.fr by Sliver91

use Azuriom\Rules\Color;

return [
    // Navigation
    'nav_logo' => ['required', 'boolean'],
    'nav_sticky' => ['required', 'boolean'],
    'theme_switch' => ['required', 'boolean'],

    // Home page header
    'title' => ['nullable', 'string', 'max:40'],
    'tagline' => ['nullable', 'string', 'max:200'],
    'address' => ['nullable', 'string', 'max:100'],
    'version' => ['nullable', 'string', 'max:20'],
    'title_size' => ['required', 'in:normal,grand,geant'],
    'show_vote_button' => ['required', 'boolean'],
    'extra_label' => ['nullable', 'string', 'max:40'],
    'extra_url' => ['nullable', 'url', 'max:200'],
    'show_online' => ['required', 'boolean'],
    'show_votes' => ['required', 'boolean'],

    // Home page sections
    'show_news' => ['required', 'boolean'],
    'show_goal' => ['required', 'boolean'],
    'show_top' => ['required', 'boolean'],
    'top_count' => ['required', 'in:3,5,10'],
    'show_servers' => ['required', 'boolean'],
    'show_steps' => ['required', 'boolean'],
    'discord' => ['nullable', 'url', 'max:200'],

    // Appearance
    'background' => ['required', 'in:none,violet,bleu,emeraude,braise,aube,etoiles'],
    'veil' => ['required', 'in:leger,moyen,fort'],
    'radius' => ['required', 'in:droit,leger,rond'],
    'title_font' => ['required', 'in:condense,large,technique'],
    'color_dark' => ['required', new Color()],
    'color_light' => ['required', new Color()],
    'default_dark' => ['required', 'boolean'],

    // Footer
    'footer_text' => ['nullable', 'string', 'max:300'],
    'show_social' => ['required', 'boolean'],
    'footer_links' => ['nullable', 'array', 'max:8'],
    'footer_links.*.name' => ['required', 'string', 'max:40'],
    'footer_links.*.value' => ['required', 'url', 'max:200'],
];
