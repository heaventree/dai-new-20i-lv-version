<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function uploadVideo(Request $request, CmsPage $cmsPage)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,webm|max:51200',
        ]);

        $file     = $request->file('video');
        $filename = $cmsPage->slug . '.' . $file->getClientOriginalExtension();
        $dest     = public_path('videos/pages');
        if (!File::isDirectory($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        $oldJson = $cmsPage->content_json ?? [];
        if (!empty($oldJson['video_file'])) {
            $oldPath = public_path(ltrim($oldJson['video_file'], '/'));
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $file->move($dest, $filename);

        $oldJson['video_file'] = '/videos/pages/' . $filename;
        $oldJson['video_file_name'] = $file->getClientOriginalName();
        $oldJson['video_file_size'] = filesize($dest . '/' . $filename);
        $cmsPage->update(['content_json' => $oldJson]);

        return back()->with('success', 'Video uploaded successfully.');
    }

    public function removeVideo(CmsPage $cmsPage)
    {
        $json = $cmsPage->content_json ?? [];
        if (!empty($json['video_file'])) {
            $path = public_path(ltrim($json['video_file'], '/'));
            if (File::exists($path)) {
                File::delete($path);
            }
            unset($json['video_file'], $json['video_file_name'], $json['video_file_size']);
            $cmsPage->update(['content_json' => $json]);
        }

        return back()->with('success', 'Video file removed.');
    }
}
