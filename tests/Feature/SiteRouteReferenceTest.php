<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SiteRouteReferenceTest extends TestCase
{
    public function test_blade_route_references_use_registered_routes(): void
    {
        $registeredNames = collect(Route::getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->values();

        $bladeFiles = collect(File::allFiles(resource_path('views')))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'));

        foreach ($bladeFiles as $bladeFile) {
            $contents = file_get_contents($bladeFile->getPathname());
            preg_match_all('/route\(\s*[\'"]([^\'"]+)[\'"]/', $contents, $matches);

            foreach ($matches[1] as $routeName) {
                $this->assertTrue(
                    $registeredNames->contains($routeName),
                    "Blade file [{$bladeFile->getPathname()}] references missing route [{$routeName}]."
                );
            }
        }
    }

    public function test_hardcoded_internal_links_use_registered_get_routes(): void
    {
        $getRoutes = collect(Route::getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true))
            ->map(fn ($route) => '/' . ltrim($route->uri(), '/'))
            ->values();

        $bladeFiles = collect(File::allFiles(resource_path('views')))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'));

        foreach ($bladeFiles as $bladeFile) {
            $contents = file_get_contents($bladeFile->getPathname());
            preg_match_all('/href=["\'](\/[^"\']*)["\']/', $contents, $matches);

            foreach ($matches[1] as $href) {
                if ($href === '/' || str_contains($href, '{{') || str_starts_with($href, '//')) {
                    continue;
                }

                $path = '/' . ltrim(parse_url($href, PHP_URL_PATH) ?? '', '/');

                $this->assertTrue(
                    $getRoutes->contains($path),
                    "Blade file [{$bladeFile->getPathname()}] links to missing GET route [{$path}]."
                );
            }
        }
    }
}
