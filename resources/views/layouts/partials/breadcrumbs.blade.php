@php($breadcrumbs = \App\Support\ScoolaBreadcrumbs::build(request(), $viewData ?? []))

@if (! empty($breadcrumbs))
    <nav class="scoola-breadcrumbs" aria-label="Breadcrumb">
        @foreach ($breadcrumbs as $crumb)
            @if (! $loop->first)
                <span class="scoola-breadcrumb-separator" aria-hidden="true">/</span>
            @endif

            @if (! empty($crumb['url']))
                <a href="{{ $crumb['url'] }}" class="scoola-breadcrumb-link">{{ $crumb['label'] }}</a>
            @else
                <span class="scoola-breadcrumb-current">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
