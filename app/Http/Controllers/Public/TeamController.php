<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
        return view('public.team', compact('members'));
    }

    public function show(string $slug)
    {
        $member = TeamMember::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('public.team-show', compact('member'));
    }
}
