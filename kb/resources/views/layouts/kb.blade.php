<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AcelleMail Knowledge Base')</title>
    <meta name="description" content="@yield('meta_description', 'Tutorials, guides, and references for AcelleMail — the self-hosted email marketing platform.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="AcelleMail Knowledge Base">
    <meta property="og:title" content="@yield('og_title', 'AcelleMail Knowledge Base')">
    <meta property="og:description" content="@yield('meta_description', 'Tutorials, guides, and references for AcelleMail — the self-hosted email marketing platform.')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', 'https://acellemail.com/images/og/og-default.svg')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="en_US">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'AcelleMail Knowledge Base')">
    <meta name="twitter:description" content="@yield('meta_description', 'Tutorials, guides, and references for AcelleMail — the self-hosted email marketing platform.')">
    <meta name="twitter:image" content="@yield('og_image', 'https://acellemail.com/images/og/og-default.svg')">

    {{-- Structured Data --}}
    @stack('jsonld')

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Prism.js — Tomorrow theme for code highlighting --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">

    <style>
        /* ============================================================
           AcelleMail KB — Full Design System (Inline CSS)
           ============================================================ */

        /* --- Reset & Base --- */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #241C15;
            background-color: #FEF7F2;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a {
            color: #E8571A;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        a:hover {
            color: #c44a15;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* --- Typography — Headings --- */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 600;
            line-height: 1.25;
            color: #241C15;
        }

        h1 { font-size: 2.5rem; letter-spacing: -0.02em; }
        h2 { font-size: 1.875rem; letter-spacing: -0.015em; }
        h3 { font-size: 1.5rem; }
        h4 { font-size: 1.25rem; }

        /* --- Layout --- */
        .kb-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .kb-main {
            min-height: calc(100vh - 160px);
            padding-top: 32px;
            padding-bottom: 64px;
        }

        .kb-article-layout {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 48px;
            align-items: start;
        }

        .kb-article-layout__content {
            min-width: 0;
        }

        .kb-article-layout__sidebar {
            position: sticky;
            top: 88px;
        }

        .kb-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .kb-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .kb-sidebar-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 48px;
            align-items: start;
        }

        .kb-sidebar-layout__main {
            min-width: 0;
        }

        .kb-sidebar-layout__aside {
            position: sticky;
            top: 88px;
        }

        /* --- Prose (Article Body) --- */
        .kb-prose {
            font-size: 1.0625rem;
            line-height: 1.75;
            color: #241C15;
        }

        .kb-prose > * + * {
            margin-top: 1.5em;
        }

        .kb-prose h2 {
            font-size: 1.75rem;
            margin-top: 2.5em;
            margin-bottom: 0.75em;
            padding-bottom: 0.4em;
            border-bottom: 1px solid #E5E0DA;
        }

        .kb-prose h3 {
            font-size: 1.375rem;
            margin-top: 2em;
            margin-bottom: 0.5em;
        }

        .kb-prose h4 {
            font-size: 1.125rem;
            margin-top: 1.75em;
            margin-bottom: 0.5em;
        }

        .kb-prose p {
            margin-bottom: 1.25em;
        }

        .kb-prose a {
            color: #E8571A;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .kb-prose a:hover {
            color: #c44a15;
        }

        .kb-prose strong {
            font-weight: 600;
        }

        .kb-prose ul,
        .kb-prose ol {
            padding-left: 1.75em;
            margin-bottom: 1.25em;
        }

        .kb-prose li {
            margin-bottom: 0.5em;
        }

        .kb-prose li::marker {
            color: #E8571A;
        }

        .kb-prose blockquote {
            border-left: 4px solid #E8571A;
            padding: 1em 1.5em;
            background: #F6F1EB;
            border-radius: 0 8px 8px 0;
            color: #6E6860;
            font-style: italic;
        }

        .kb-prose blockquote p:last-child {
            margin-bottom: 0;
        }

        .kb-prose code {
            font-family: 'IBM Plex Mono', 'Fira Code', 'Consolas', monospace;
            font-size: 0.875em;
            background: #F6F1EB;
            border: 1px solid #E5E0DA;
            border-radius: 4px;
            padding: 0.15em 0.4em;
            color: #c44a15;
        }

        .kb-prose pre {
            background: #2d2d2d;
            border-radius: 8px;
            padding: 1.25em 1.5em;
            overflow-x: auto;
            margin: 1.5em 0;
            line-height: 1.5;
        }

        .kb-prose pre code {
            background: none;
            border: none;
            padding: 0;
            color: #ccc;
            font-size: 0.875rem;
        }

        .kb-prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5em 0;
            font-size: 0.9375rem;
        }

        .kb-prose thead th {
            background: #F6F1EB;
            font-weight: 600;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 2px solid #E5E0DA;
        }

        .kb-prose tbody td {
            padding: 10px 16px;
            border-bottom: 1px solid #E5E0DA;
        }

        .kb-prose tbody tr:hover {
            background: #FEF7F2;
        }

        .kb-prose img {
            border-radius: 8px;
            margin: 1.5em 0;
            box-shadow: 0 2px 8px rgba(36, 28, 21, 0.08);
        }

        .kb-prose hr {
            border: none;
            border-top: 1px solid #E5E0DA;
            margin: 2em 0;
        }

        /* --- Note/Warning/Info callouts --- */
        .kb-prose .callout {
            padding: 1em 1.25em;
            border-radius: 8px;
            margin: 1.5em 0;
            font-size: 0.9375rem;
        }

        .kb-prose .callout-info {
            background: #e8f4f8;
            border-left: 4px solid #0069ff;
        }

        .kb-prose .callout-warning {
            background: #fff8e6;
            border-left: 4px solid #e6a817;
        }

        .kb-prose .callout-danger {
            background: #fef2f2;
            border-left: 4px solid #dc3545;
        }

        /* --- Components: Card --- */
        .kb-card {
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            padding: 24px;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }

        .kb-card:hover {
            box-shadow: 0 8px 24px rgba(36, 28, 21, 0.08);
            transform: translateY(-2px);
        }

        .kb-card__title {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.35;
            margin-bottom: 8px;
        }

        .kb-card__title a {
            color: #241C15;
        }

        .kb-card__title a:hover {
            color: #E8571A;
        }

        .kb-card__excerpt {
            color: #6E6860;
            font-size: 0.9375rem;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .kb-card__meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.8125rem;
            color: #6E6860;
        }

        .kb-card__meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .kb-card__meta-sep {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #E5E0DA;
        }

        /* --- Components: Tag --- */
        .kb-tag {
            display: inline-block;
            padding: 4px 12px;
            background: #F6F1EB;
            border: 1px solid #E5E0DA;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #6E6860;
            transition: all 0.2s ease;
        }

        .kb-tag:hover {
            background: #E8571A;
            border-color: #E8571A;
            color: #fff;
        }

        /* --- Components: Badge --- */
        .kb-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            line-height: 1.5;
        }

        .kb-badge--category {
            color: #fff;
        }

        .kb-badge--status {
            font-size: 0.6875rem;
        }

        .kb-badge--draft {
            background: #F6F1EB;
            color: #6E6860;
        }

        .kb-badge--published {
            background: #dcfce7;
            color: #166534;
        }

        .kb-badge--archived {
            background: #fef2f2;
            color: #991b1b;
        }

        /* --- Components: Button --- */
        .kb-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.9375rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .kb-btn--primary {
            background: #E8571A;
            color: #fff;
        }

        .kb-btn--primary:hover {
            background: #c44a15;
            color: #fff;
        }

        .kb-btn--secondary {
            background: #fff;
            color: #241C15;
            border: 1px solid #E5E0DA;
        }

        .kb-btn--secondary:hover {
            border-color: #E8571A;
            color: #E8571A;
        }

        .kb-btn--sm {
            padding: 6px 14px;
            font-size: 0.8125rem;
        }

        .kb-btn--danger {
            background: #dc3545;
            color: #fff;
        }

        .kb-btn--danger:hover {
            background: #b02a37;
            color: #fff;
        }

        /* --- Components: Form elements --- */
        .kb-input,
        .kb-select,
        .kb-textarea {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.9375rem;
            padding: 10px 14px;
            border: 1px solid #E5E0DA;
            border-radius: 8px;
            background: #fff;
            color: #241C15;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }

        .kb-input:focus,
        .kb-select:focus,
        .kb-textarea:focus {
            outline: none;
            border-color: #E8571A;
            box-shadow: 0 0 0 3px rgba(232, 87, 26, 0.1);
        }

        .kb-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236E6860' d='M6 8.825L0.375 3.2l.85-.85L6 7.125l4.775-4.775.85.85z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .kb-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .kb-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #241C15;
            margin-bottom: 6px;
        }

        .kb-form-group {
            margin-bottom: 20px;
        }

        /* --- Header --- */
        .kb-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #fff;
            border-bottom: 1px solid #E5E0DA;
            height: 64px;
        }

        .kb-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .kb-header__logo {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #241C15;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kb-header__logo:hover {
            color: #E8571A;
        }

        .kb-header__logo-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .kb-header__logo-wordmark {
            height: 22px;
            width: auto;
        }

        .kb-header__nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .kb-header__link {
            font-size: 0.9375rem;
            font-weight: 500;
            color: #6E6860;
            position: relative;
        }

        .kb-header__link:hover,
        .kb-header__link--active {
            color: #241C15;
        }

        .kb-header__dropdown {
            position: relative;
        }

        .kb-header__dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            background: none;
            border: none;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #6E6860;
            padding: 0;
        }

        .kb-header__dropdown-toggle:hover {
            color: #241C15;
        }

        .kb-header__dropdown-toggle svg {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }

        .kb-header__dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            min-width: 600px;
            box-shadow: 0 12px 32px rgba(36, 28, 21, 0.12);
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            padding: 16px;
            padding-top: 24px;
            margin-top: 0;
        }

        /* Invisible bridge to prevent hover gap */
        .kb-header__dropdown-menu::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 12px;
        }

        .kb-header__dropdown:hover .kb-header__dropdown-menu {
            display: grid;
        }

        .kb-header__dropdown:hover .kb-header__dropdown-toggle svg {
            transform: rotate(180deg);
        }

        .kb-header__dropdown-group {
            padding: 4px 0;
        }

        .kb-header__dropdown-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #928B83;
            padding: 4px 10px 6px;
        }

        .kb-header__dropdown-item {
            display: block;
            padding: 6px 10px;
            font-size: 0.8125rem;
            color: #241C15;
            border-radius: 6px;
            transition: background 0.15s ease;
        }

        .kb-header__dropdown-item:hover {
            background: #F6F1EB;
            color: #E8571A;
        }

        .kb-header__search {
            position: relative;
        }

        .kb-header__search-input {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.875rem;
            padding: 8px 14px 8px 36px;
            border: 1px solid #E5E0DA;
            border-radius: 8px;
            background: #F6F1EB;
            width: 220px;
            color: #241C15;
            transition: all 0.2s ease;
        }

        .kb-header__search-input:focus {
            outline: none;
            border-color: #E8571A;
            background: #fff;
            width: 280px;
            box-shadow: 0 0 0 3px rgba(232, 87, 26, 0.1);
        }

        .kb-header__search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6E6860;
            pointer-events: none;
        }

        .kb-header__mobile-toggle {
            display: none;
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            color: #241C15;
        }

        /* --- Footer --- */
        .kb-footer {
            background: #241C15;
            color: #a89f94;
            padding: 64px 0 32px;
        }

        .kb-footer__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 48px;
            margin-bottom: 48px;
        }

        .kb-footer__heading {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 20px;
        }

        .kb-footer__list {
            list-style: none;
        }

        .kb-footer__list li {
            margin-bottom: 10px;
        }

        .kb-footer__list a {
            color: #a89f94;
            font-size: 0.9375rem;
            transition: color 0.2s ease;
        }

        .kb-footer__list a:hover {
            color: #E8571A;
        }

        .kb-footer__bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
        }

        .kb-footer__powered {
            color: #6E6860;
        }

        .kb-footer__powered a {
            color: #E8571A;
        }

        /* --- Hero --- */
        .kb-hero {
            background: linear-gradient(135deg, #241C15 0%, #3d2f23 100%);
            color: #fff;
            padding: 80px 0;
            text-align: center;
            margin: -32px -24px 48px;
            padding-left: 24px;
            padding-right: 24px;
        }

        .kb-hero h1 {
            color: #fff;
            font-size: 3rem;
            margin-bottom: 16px;
        }

        .kb-hero p {
            color: #a89f94;
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto 32px;
        }

        .kb-hero__search {
            max-width: 520px;
            margin: 0 auto;
            position: relative;
        }

        .kb-hero__search input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transition: all 0.2s ease;
        }

        .kb-hero__search input::placeholder {
            color: #a89f94;
        }

        .kb-hero__search input:focus {
            outline: none;
            border-color: #E8571A;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(232, 87, 26, 0.15);
        }

        .kb-hero__search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a89f94;
            pointer-events: none;
        }

        /* --- Section Headers --- */
        .kb-section {
            margin-bottom: 48px;
        }

        .kb-section__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .kb-section__title {
            font-size: 1.5rem;
        }

        .kb-section__link {
            font-size: 0.875rem;
            font-weight: 500;
            color: #E8571A;
        }

        /* --- Category Card (for grid) --- */
        .kb-cat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .kb-cat-card:hover {
            border-color: #E8571A;
            box-shadow: 0 4px 12px rgba(232, 87, 26, 0.08);
            transform: translateY(-1px);
        }

        .kb-cat-card__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kb-cat-card__dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .kb-cat-card__info h3 {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #241C15;
            margin-bottom: 2px;
        }

        .kb-cat-card__info span {
            font-size: 0.8125rem;
            color: #6E6860;
        }

        /* --- TOC Sidebar --- */
        .kb-toc {
            padding: 20px;
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
        }

        .kb-toc__title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6E6860;
            margin-bottom: 12px;
        }

        .kb-toc__list {
            list-style: none;
        }

        .kb-toc__list li {
            margin-bottom: 4px;
        }

        .kb-toc__list a {
            display: block;
            padding: 4px 10px;
            font-size: 0.8125rem;
            color: #6E6860;
            border-radius: 4px;
            border-left: 2px solid transparent;
            transition: all 0.15s ease;
        }

        .kb-toc__list a:hover {
            color: #E8571A;
            background: #FEF7F2;
        }

        .kb-toc__list a.active {
            color: #E8571A;
            border-left-color: #E8571A;
            background: #FEF7F2;
            font-weight: 500;
        }

        .kb-toc__list .kb-toc__h3 {
            padding-left: 22px;
        }

        /* --- Filter Bar --- */
        .kb-filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .kb-filter-bar .kb-select {
            width: auto;
            min-width: 160px;
            padding: 8px 36px 8px 12px;
            font-size: 0.875rem;
        }

        .kb-filter-bar__label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6E6860;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* --- Breadcrumbs --- */
        .kb-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: #6E6860;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .kb-breadcrumbs a {
            color: #6E6860;
        }

        .kb-breadcrumbs a:hover {
            color: #E8571A;
        }

        .kb-breadcrumbs__sep {
            color: #E5E0DA;
            font-size: 0.75rem;
        }

        .kb-breadcrumbs__current {
            color: #241C15;
            font-weight: 500;
        }

        /* --- Article Header --- */
        .kb-article-header {
            margin-bottom: 40px;
        }

        .kb-article-header h1 {
            font-size: 2.25rem;
            margin: 12px 0 16px;
        }

        .kb-article-header__meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.875rem;
            color: #6E6860;
        }

        .kb-article-header__meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* --- Tags Section --- */
        .kb-tags-section {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #E5E0DA;
        }

        .kb-tags-section__title {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6E6860;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 12px;
        }

        .kb-tags-section__list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* --- Author Bio --- */
        .kb-author {
            display: flex;
            gap: 16px;
            padding: 24px;
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            margin-top: 40px;
        }

        .kb-author__avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #F6F1EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #E8571A;
            flex-shrink: 0;
            overflow: hidden;
        }

        .kb-author__avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kb-author__info h4 {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .kb-author__info p {
            font-size: 0.875rem;
            color: #6E6860;
            line-height: 1.5;
        }

        /* --- Sidebar Widgets --- */
        .kb-widget {
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .kb-widget__title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6E6860;
            margin-bottom: 14px;
        }

        .kb-widget__list {
            list-style: none;
        }

        .kb-widget__list li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.875rem;
            color: #241C15;
            border-bottom: 1px solid #F6F1EB;
        }

        .kb-widget__list li:last-child a {
            border-bottom: none;
        }

        .kb-widget__list li a:hover {
            color: #E8571A;
        }

        .kb-widget__list li a span.count {
            font-size: 0.75rem;
            color: #6E6860;
            background: #F6F1EB;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* --- Category Header (listing page) --- */
        .kb-category-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #E5E0DA;
        }

        .kb-category-header__icon {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .kb-category-header__info h1 {
            font-size: 2rem;
            margin-bottom: 4px;
        }

        .kb-category-header__info p {
            color: #6E6860;
            font-size: 1rem;
        }

        /* --- Pagination --- */
        .kb-pagination {
            margin-top: 48px;
        }

        .kb-pagination nav {
            display: flex;
            justify-content: center;
        }

        .kb-pagination .flex {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .kb-pagination span[aria-current="page"] span,
        .kb-pagination a {
            padding: 8px 14px;
            font-size: 0.875rem;
            border-radius: 6px;
        }

        .kb-pagination span[aria-current="page"] span {
            background: #E8571A;
            color: #fff;
            font-weight: 600;
        }

        .kb-pagination a {
            color: #6E6860;
            border: 1px solid #E5E0DA;
            transition: all 0.2s ease;
        }

        .kb-pagination a:hover {
            border-color: #E8571A;
            color: #E8571A;
        }

        .kb-pagination svg {
            width: 16px;
            height: 16px;
        }

        .kb-pagination p {
            font-size: 0.875rem;
            color: #928B83;
            text-align: center;
            margin-bottom: 12px;
        }

        .kb-pagination .hidden {
            display: none;
        }

        /* --- Admin Table --- */
        .kb-admin-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #E5E0DA;
            border-radius: 12px;
            overflow: hidden;
        }

        .kb-admin-table thead th {
            background: #F6F1EB;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6E6860;
            border-bottom: 1px solid #E5E0DA;
        }

        .kb-admin-table tbody td {
            padding: 14px 16px;
            font-size: 0.9375rem;
            border-bottom: 1px solid #F6F1EB;
            vertical-align: middle;
        }

        .kb-admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .kb-admin-table tbody tr:hover {
            background: #FEF7F2;
        }

        .kb-admin-table__actions {
            display: flex;
            gap: 8px;
        }

        /* --- Search Results --- */
        .kb-search-header {
            margin-bottom: 32px;
        }

        .kb-search-header h1 {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .kb-search-header p {
            color: #6E6860;
            font-size: 1rem;
        }

        .kb-search-form {
            max-width: 600px;
            margin-bottom: 32px;
            position: relative;
        }

        .kb-search-form input {
            width: 100%;
            padding: 12px 20px 12px 44px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 1rem;
            border: 1px solid #E5E0DA;
            border-radius: 10px;
            background: #fff;
            color: #241C15;
        }

        .kb-search-form input:focus {
            outline: none;
            border-color: #E8571A;
            box-shadow: 0 0 0 3px rgba(232, 87, 26, 0.1);
        }

        .kb-search-form__icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6E6860;
        }

        /* --- Checkbox group --- */
        .kb-checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .kb-checkbox-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .kb-checkbox-group input[type="checkbox"] {
            accent-color: #E8571A;
            width: 16px;
            height: 16px;
        }

        /* --- Utility --- */
        .text-muted { color: #6E6860; }
        .text-small { font-size: 0.875rem; }
        .mt-8 { margin-top: 8px; }
        .mt-16 { margin-top: 16px; }
        .mt-24 { margin-top: 24px; }
        .mt-32 { margin-top: 32px; }
        .mt-48 { margin-top: 48px; }
        .mb-8 { margin-bottom: 8px; }
        .mb-16 { margin-bottom: 16px; }
        .mb-24 { margin-bottom: 24px; }
        .mb-32 { margin-bottom: 32px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-8 { gap: 8px; }
        .gap-16 { gap: 16px; }
        .gap-24 { gap: 24px; }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .kb-article-layout {
                grid-template-columns: 1fr;
            }

            .kb-article-layout__sidebar {
                display: none;
            }

            .kb-sidebar-layout {
                grid-template-columns: 1fr;
            }

            .kb-sidebar-layout__aside {
                position: static;
            }

            .kb-grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            h2 { font-size: 1.5rem; }

            .kb-hero {
                padding: 48px 0;
            }

            .kb-hero h1 {
                font-size: 2rem;
            }

            .kb-hero p {
                font-size: 1rem;
            }

            .kb-grid-3 {
                grid-template-columns: 1fr;
            }

            .kb-grid-2 {
                grid-template-columns: 1fr;
            }

            .kb-header__nav {
                display: none;
            }

            .kb-header__search {
                display: none;
            }

            .kb-header__mobile-toggle {
                display: flex;
            }

            .kb-header--mobile-open .kb-header__nav {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                background: #fff;
                border-bottom: 1px solid #E5E0DA;
                padding: 16px 24px;
                gap: 16px;
                box-shadow: 0 8px 24px rgba(36, 28, 21, 0.1);
            }

            .kb-header--mobile-open .kb-header__search {
                display: block;
                position: absolute;
                top: calc(64px + var(--nav-height, 0px));
                left: 0;
                right: 0;
                padding: 0 24px 16px;
                background: #fff;
            }

            .kb-header--mobile-open .kb-header__search-input {
                width: 100%;
            }

            .kb-footer__grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .kb-footer__bottom {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .kb-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .kb-filter-bar .kb-select {
                width: 100%;
            }

            .kb-category-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .kb-article-header h1 {
                font-size: 1.75rem;
            }

            .kb-author {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .kb-container {
                padding: 0 16px;
            }

            .kb-hero h1 {
                font-size: 1.625rem;
            }

            .kb-card {
                padding: 18px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @include('partials.header')

    <main class="kb-main">
        <div class="kb-container">
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

    {{-- Prism.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-yaml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-nginx.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.kb-header__mobile-toggle');
            const header = document.querySelector('.kb-header');

            if (toggle) {
                toggle.addEventListener('click', function() {
                    header.classList.toggle('kb-header--mobile-open');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
