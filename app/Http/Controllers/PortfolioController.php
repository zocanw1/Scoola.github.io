<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function show()
    {
        $teamMembers = TeamMember::orderBy('role', 'desc')->orderBy('created_at')->get();
        return view('Portofolio', compact('teamMembers'));
    }
}
