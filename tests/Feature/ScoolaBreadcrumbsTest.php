<?php

namespace Tests\Feature;

use App\Support\ScoolaBreadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ScoolaBreadcrumbsTest extends TestCase
{
    public function test_guru_presensi_room_breadcrumbs_do_not_append_subject_label(): void
    {
        $request = Request::create('/guru/presensi/ruang-kelas/8', 'GET');
        $route = Route::getRoutes()->match($request);
        $request->setRouteResolver(static fn () => $route);

        $breadcrumbs = ScoolaBreadcrumbs::build($request, [
            'guru' => (object) ['nama_guru' => 'ZASKIA CILLA RADHITA'],
            'breadcrumbSubject' => 'XI-SIJA 2',
        ]);

        $this->assertSame(
            ['Guru', 'Presensi', 'Ruang Kelas'],
            array_column($breadcrumbs, 'label')
        );
    }
}
