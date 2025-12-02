<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReviewStatusUpdateMail;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReviewAdminController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'livro'])
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:1,2',
            'justification' => 'required_if:status,2',
        ]);

        $review->update([
            'status' => $request->status,
            'justification' => $request->justification,
        ]);

        Mail::to($review->user->email)
            ->send(new ReviewStatusUpdateMail($review));

        return back()->with('success', 'Estado atualizado com sucesso!');
    }
}
