<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMembersController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('display_order')->orderBy('name')->get();
        return view('admin.team-members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'nullable|string|max:255',
            'bio'           => 'nullable|string',
            'website'       => 'nullable|url|max:255',
            'display_order' => 'nullable|integer',
        ]);

        $slug = TeamMember::generateSlug($request->name);

        TeamMember::create([
            'name'          => $request->name,
            'slug'          => $slug,
            'title'         => $request->title,
            'bio'           => $request->bio,
            'photo'         => $request->photo_data ?: null,
            'website'       => $request->website ?: null,
            'display_order' => $request->display_order ?? 0,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member added successfully.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'nullable|string|max:255',
            'bio'           => 'nullable|string',
            'website'       => 'nullable|url|max:255',
            'display_order' => 'nullable|integer',
        ]);

        $data = [
            'name'          => $request->name,
            'title'         => $request->title,
            'bio'           => $request->bio,
            'website'       => $request->website ?: null,
            'display_order' => $request->display_order ?? 0,
            'is_active'     => $request->has('is_active'),
        ];

        if ($request->filled('photo_data')) {
            $data['photo'] = $request->photo_data;
        } elseif ($request->has('remove_photo')) {
            $data['photo'] = null;
        }

        $teamMember->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return redirect()->route('admin.team-members.index')->with('success', 'Team member removed.');
    }
}
