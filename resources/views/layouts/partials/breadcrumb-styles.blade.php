<style>
    .scoola-breadcrumbs {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        min-width: 0;
        font-size: 13px;
        font-weight: 900;
        line-height: 1.4;
        color: var(--midnight);
    }

    .scoola-breadcrumb-link,
    .scoola-breadcrumb-current {
        display: inline-flex;
        align-items: center;
        min-height: 32px;
        padding: 6px 12px;
        border: 3px solid var(--midnight);
        border-radius: 999px;
        background: var(--white);
        box-shadow: 3px 3px 0 var(--midnight);
        text-decoration: none;
        color: var(--midnight);
    }

    .scoola-breadcrumb-link {
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .scoola-breadcrumb-link:hover {
        background: var(--cyber);
        transform: translate(-2px, -2px);
        box-shadow: 5px 5px 0 var(--midnight);
    }

    .scoola-breadcrumb-link:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 var(--midnight);
    }

    .scoola-breadcrumb-current {
        background: var(--gold);
    }

    .scoola-breadcrumb-separator {
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        color: var(--cosmo);
    }

    .scoola-breadcrumb-shell {
        display: flex;
        align-items: center;
        min-width: 0;
    }

    .page-crumb-bar {
        padding: 18px 16px 0;
    }

    @media (max-width: 768px) {
        .scoola-breadcrumbs {
            gap: 6px;
            font-size: 12px;
        }

        .scoola-breadcrumb-link,
        .scoola-breadcrumb-current {
            min-height: 28px;
            padding: 5px 10px;
            max-width: 100%;
        }

        .page-crumb-bar {
            padding: 16px 16px 0;
        }
    }
</style>
