<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
class ContactSubmissionsController extends Controller
{
    public function index(Request $request)
    {
        $submissions = ContactSubmission::latest()->paginate(20);
        return view('admin.contact-submissions.index', compact('submissions'));
    }
    public function show(ContactSubmission $contactSubmission)
    {
        $contactSubmission->update(['read' => true]);
        return view('admin.contact-submissions.show', compact('contactSubmission'));
    }
}
