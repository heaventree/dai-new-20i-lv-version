<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::with('application')->latest()->paginate(20);
        $total = Payment::where('status','succeeded')->sum('amount');
        return view('admin.payments.index', compact('payments', 'total'));
    }
}
