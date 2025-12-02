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
            'justification' => 'nullable|string|max:500'
        ]);

        $review->status = $request->status;
        $review->justification = $request->justification;
        $review->save();

        // Enviar email ao utilizador da review
        Mail::to($review->user->email)
            ->send(new ReviewStatusUpdateMail($review));

        return back()->with('success', 'Estado da avaliação atualizado com sucesso!');
    }

}
