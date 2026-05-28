<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class PortfolioController extends Controller
{
    public function show()
    {
        $teamMembers = $this->loadTeamMembers();

        return view('Portofolio', compact('teamMembers'));
    }

    private function loadTeamMembers(): Collection
    {
        return collect(config('team_members.members', []))
            ->map(fn (array $member) => (object) $member)
            ->sortByDesc(fn (object $member) => $member->role)
            ->values();
    }
}
