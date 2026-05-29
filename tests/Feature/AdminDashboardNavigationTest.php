<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminDashboardNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_recent_attendance_link_uses_existing_rekap_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.rekap.index', [], false), false);
        $response->assertDontSee('/admin/absensi', false);
    }

    public function test_hardcoded_admin_dashboard_links_resolve_to_registered_get_routes(): void
    {
        $contents = file_get_contents(resource_path('views/admin/dashboard.blade.php'));
        preg_match_all('/href=["\'](\/admin\/[^"\']+)["\']/', $contents, $matches);

        $routes = collect(Route::getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true))
            ->map(fn ($route) => '/' . ltrim($route->uri(), '/'))
            ->values();

        foreach ($matches[1] as $path) {
            $this->assertTrue(
                $routes->contains($path),
                "Hardcoded dashboard link [{$path}] is not registered as a GET route."
            );
        }
    }
}
