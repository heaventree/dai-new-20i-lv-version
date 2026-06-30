<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServicesAdminController extends Controller
{
    public function index()
    {
        $services = CmsPage::where('slug', 'like', 'service-%')
            ->orderBy('slug')
            ->get();

        return response()
            ->view('admin.services.index', compact('services'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function uploadImage(Request $request, CmsPage $cmsPage)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $dest = public_path('images/services');
        if (!File::isDirectory($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        // Remove any previous custom image(s) for this slug (any extension) to avoid orphaned files
        foreach (File::glob($dest . '/' . $cmsPage->slug . '.*') as $existing) {
            File::delete($existing);
        }

        $file     = $request->file('image');
        $filename = $cmsPage->slug . '.' . $file->getClientOriginalExtension();
        $file->move($dest, $filename);

        $cmsPage->update(['image_path' => '/images/services/' . $filename]);

        return back()->with('success', 'Image updated for "' . $cmsPage->title . '".');
    }

    public function removeImage(CmsPage $cmsPage)
    {
        if ($cmsPage->image_path) {
            $full = public_path(ltrim($cmsPage->image_path, '/'));
            if (File::exists($full)) {
                File::delete($full);
            }
            $cmsPage->update(['image_path' => null]);
        }

        return back()->with('success', 'Custom image removed for "' . $cmsPage->title . '".');
    }

    public function destroy(CmsPage $cmsPage)
    {
        if ($cmsPage->image_path) {
            $full = public_path(ltrim($cmsPage->image_path, '/'));
            if (File::exists($full)) {
                File::delete($full);
            }
        }

        $title = $cmsPage->title;
        $cmsPage->delete();

        return redirect()->route('admin.services.index')
            ->with('success', '"' . $title . '" has been deleted.');
    }
}
