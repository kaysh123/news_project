<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Cetegory\Cetegory;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;


class CategoryController extends Controller
{
    public function index()
    {
        // $users = User::paginate(1);
        $cetegories = Cetegory::paginate(50);
        return view('categories.index', ['cetegories' => $cetegories,]);
    }
    public function addCetegory(Request $request)
    {
        $cetegories = new Cetegory;
        $cetegories->title = $request->title;
        $cetegories->save();
        return redirect('/categories');
    }
    public function cetegoryDelete(Request $request, $id)
    {
        try {
            // Perform the delete operation
            $cetegories = Cetegory::find($id); // Corrected to use $id instead of $request->input('user_id')

            if (!$cetegories) {
                return response()->json(['success' => false, 'message' => 'Category not found.']);
            }

            $cetegories->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    // public function hitApi(Request $request)
    // {
    //     // Construct URL with API key
    //     $url = 'https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8';

    //     // Make API request
    //     $response = Http::get($url);

    //     // Process the response as needed
    //     return $response->json();
    // }
    public function hitApi($title)
    {
        // Encode the title to make it URL-safe
        $encodedTitle = urlencode($title);

        // Construct the URL with the dynamic title parameter
        //$response = Http::get('https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=Politics&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8');
        //dd($response);
        //$response = Http::get('https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=Politics&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8');
        $encodedTitle = urlencode($title);

        // Construct the URL with the dynamic title parameter
        $url = "https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=$encodedTitle&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8";

        // Make the API request
        $response = Http::get($url);
        if ($response->successful()) {
            $articles = $response->json()['articles'];
            //dd($articles);
            foreach ($articles as $articleData) {

                News::updateOrCreate([
                    'cetegory_id' => $title, // Assuming the category ID is 1
                    'title' => $articleData['title'],
                    'country' => $articleData['country'],
                    'auther' => $articleData['authorsByline'],
                    'publish' => $articleData['pubDate'],
                    'description' => $articleData['description'],
                    'content' => $articleData['content'],
                    'image' => $articleData['imageUrl'],
                    'url' => $articleData['url'],
                    // Add other fields here
                ]);
            }

            return response()->json(['message' => 'Data stored successfully']);
        } else {
            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        }
    }
}
