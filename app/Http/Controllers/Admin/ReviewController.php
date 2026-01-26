<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->paginate(25);
        return view('admin.reviews.index', compact('reviews'));
    }
}
