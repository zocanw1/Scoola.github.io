<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Support\Collection;
use Throwable;

class PortfolioController extends Controller
{
    public function show()
    {
        $teamMembers = $this->loadTeamMembers();

        return view('Portofolio', compact('teamMembers'));
    }

    private function loadTeamMembers(): Collection
    {
        try {
            return TeamMember::query()
                ->orderBy('role', 'desc')
                ->orderBy('created_at')
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            return collect();
        }
    }
}
