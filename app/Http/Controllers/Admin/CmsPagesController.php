<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
class CmsPagesController extends Controller
{
    public function index() { return view('admin.cms-pages.index', ['pages' => CmsPage::all()]); }
    public function edit(CmsPage $cmsPage) { return view('admin.cms-pages.edit', ['page' => $cmsPage]); }
    public function update(Request $request, CmsPage $cmsPage)
    {
        $request->validate([
            'title'            => 'required',
            'content'          => 'nullable',
            'meta_description' => 'nullable|max:160',
            'content_json'     => 'nullable|json',
            'is_published'     => 'nullable|boolean',
        ]);
        $data = $request->only(['title', 'content', 'meta_description']);
        $data['is_published'] = $request->boolean('is_published');
        if ($request->filled('content_json')) {
            $data['content_json'] = json_decode($request->input('content_json'), true);
        }
        $cmsPage->update($data);
        return back()->with('success', 'Page updated successfully.');
    }
}
