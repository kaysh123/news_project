<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\Cetegory\Cetegory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        //$cetegories = Cetegory::first(); // Fetch the first category record
        //$title = $cetegories ? $cetegories->title : 'Default Title';
        $categories = Cetegory::all();
        $news = News::orderBy('created_at', 'desc')->limit(1000)->get();

        return view('news.index', ['allnews' => $news,'categories'=>$categories]); // 'cetegories' => $cetegories, 'title' => $title,]);
    }
    public function addNews(Request $request)
    {
        $news = new News;
        $news->cetegory_id = $request->cetegory_id;
        $news->title = $request->title;
        $news->country = $request->country;
        $news->auther = $request->auther;
        $news->publish = $request->publish;
        $news->description = $request->description;
        $news->content = $request->content;
        $news->image = $request->image;
        $news->save();
        return redirect('/news');
    }
    public function deleteNews(Request $request, $id)
    {
        // Validation
        $request->validate([
            'news_id' => 'required|exists:news,id',
        ]);

        try {
            // Perform the delete operation
            $news = News::find($request->input('news_id'));
            $news->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
