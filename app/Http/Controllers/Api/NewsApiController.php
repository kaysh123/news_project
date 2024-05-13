<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\News;
use Illuminate\Support\Facades\Http;
use App\Models\Cetegory\Cetegory;
use Illuminate\Support\Facades\DB;
use App\Models\Api\ApiSelect;

class NewsApiController extends Controller
{
    public function index()
    {
        return News::all();
    }
    //
    public function fetch_news(Request $request)
    {
        // return $request->input('category');
        $query = $request->input('category');
        $region = $request->input('region');
        $news = News::where('cetegory_id', $query)
        ->orWhere('description', 'like', "%$query%");

        if ($region) 
        {
            $news = $news->where('region', 'like', "%$region%");
        }

        $news = $news->orderBy('created_at', 'desc')->paginate(15);
        if ($news->isEmpty()) {
            // If no news found, fetch news from the Perigon API
            $countryName = urlencode($query);
            $api = ApiSelect::where('status', 1)->first();
            $apiKey = urlencode($api->api_key);
            if ($api->api_name === 'Perigon') {
                //Start of Perigon API Response
                if (question == "Headlines") {
                    $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=none&q=$countryName&size=50&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
                } else if (question == "Crime") {
                    $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&&category=none&q=$countryName&size=50&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
                } else {
                    $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=$countryName&size=50&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
                }
                // Check if the response is successful
                if ($response->successful()) {
                    $articles = $response->json()['articles'];

                    // Store fetched news in the database
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['categories'][0]['name'])) {
                            $firstCategoryName = $articleData['categories'][0]['name'];
                        }
                        //old to add news into database
                        /* News::create([
                            'cetegory_id' => $countryName, // Assuming the category ID is
                            'title' => $articleData['title'],
                            'country' => $articleData['country'],
                            'auther' => $articleData['authorsByline'],
                            'publish' => $articleData['pubDate'],
                            'description' => $articleData['description'],
                            'content' => $articleData['content'],
                            'image' => $articleData['imageUrl'],
                            'url' => $articleData['url'],
                            // Add other fields here
                        ]);*/
                        News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'cetegory_id' => $countryName, // Assuming the category ID is
                                'title' => $articleData['title'],
                                'country' => $articleData['country'],
                                'auther' => $articleData['authorsByline'],
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'],
                                'content' => $articleData['content'],
                                'image' => $articleData['imageUrl'],
                                'url' => $articleData['url'],
                                'region'=>$articleData['ai_region']
                                // Add other fields here
                            ]
                        );

                    }

                    // Fetch newly inserted news
                    $news = News::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data fetched successfully',
                        'newsList' => $news
                    ]);
                    // Return paginated news
                    //return response()->json($news);
                } else {
                    // If unsuccessful, return an error response
                    return response()->json(['error' => 'Failed to fetch news from Perigon API'], 500);
                }
                //End of Perigon API Response
            } else {
                if($region)
                {
                    $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&size=50&q=$countryName&country=us&region=$region");
                }
                else
                {
                    $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&size=50&q=$countryName&country=us");
                }

                //Start of News Data.io API Response
                if ($response->successful()) {
                    $articles = $response->json()['results'];
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['category'][0])) {
                            $firstCategoryName = $articleData['category'][0];
                        }
                        $country = implode(',', $articleData['country']);
                        //$country = implode(',', $articleData['creator']);
                        //old method to add news
                        /* $news = News::create([
                            'cetegory_id' => $firstCategoryName, // Assuming the category ID is
                            'title' => $articleData['title'],
                            'country' => $country, // Assuming country is an array
                            'auther' => $articleData['creator'][0] ?? 'N/A', // Assuming this field exists in the response
                            'publish' => $articleData['pubDate'],
                            'description' => $articleData['description'] ?? 'N/A',
                            'content' => $articleData['content'] ?? 'N/A',
                            'image' => $articleData['image_url'] ?? 'N/A',
                            'url' => $articleData['link'] ?? 'N/A',
                        ]);*/
                        //new method to add update news
                        News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'cetegory_id' => $firstCategoryName,
                                'title' => $articleData['title'],
                                'country' => $country,
                                'auther' => $articleData['creator'][0] ?? 'N/A',
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'] ?? 'N/A',
                                'content' => $articleData['content'] ?? 'N/A',
                                'image' => $articleData['image_url'] ?? 'N/A',
                                'url' => $articleData['link'] ?? 'N/A',
                                'region'=>$articleData['ai_region'] ?? 'N/A',
                                // Add other fields here
                            ]
                        );

                        
                    }
                    $news = News::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%");
                       
                    if($region)
                    {
                        $news = $news->where('region','like', "%$query%");
                    }
                    $news = $news->orderBy('created_at', 'desc')->paginate(15);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data fetched successfully',
                        'categories' => $news
                    ]);
                }
            }
        } 
        else 
        {
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'newsList' => $news
            ]);
        }
    }

    public function fetch_cat(Request $request)
    {
        // Fetch all categories
        $categories = Cetegory::all();
        // Extract category titles
        $categoryTitles = $categories->pluck('title');
        // Return the response in JSON format
        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'categories' => $categoryTitles
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = News::findOrFail($id);
        $task->update($request->all());
        return $task;
    }

    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
       public function search(Request $request)
    {
        $query = $request->input('query');

        // $newsQuery = News::orderBy('created_at', 'desc');

        // if (!empty($query)) {
        //     $newsQuery->where('title', 'like', "%$query%")->orWhere('description', 'like', "%$query%");
        // }

        // $news = $newsQuery->paginate(15);
         $news = News::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        if ($news->isEmpty()) {
            $api = ApiSelect::where('status', 1)->first();
            $countryName = urlencode($query);
            $apiKey = urlencode($api->api_key);
            if ($api->api_name === 'Perigon') {
                // If no news found, fetch news from the Perigon API
                //Start of Perigon API Response
                $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&q=$countryName&size=50&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
                // Check if the response is successful
                if ($response->successful()) {
                    $articles = $response->json()['articles'];

                    // Store fetched news in the database
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['categories'][0]['name'])) {
                            $firstCategoryName = $articleData['categories'][0]['name'];
                        }
                        //old code to add data into db
                        /*News::create([
                            'cetegory_id' => $firstCategoryName, // Assuming the category ID is
                            'title' => $articleData['title'],
                            'country' => $articleData['country'],
                            'auther' => $articleData['authorsByline'],
                            'publish' => $articleData['pubDate'],
                            'description' => $articleData['description'],
                            'content' => $articleData['content'],
                            'image' => $articleData['imageUrl'],
                            'url' => $articleData['url'],
                            // Add other fields here
                        ]);*/
                        //new code to add create
                        News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'cetegory_id' => $firstCategoryName,
                                'title' => $articleData['title'],
                                'country' => $articleData['country'],
                                'auther' => $articleData['authorsByline'],
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'],
                                'content' => $articleData['content'],
                                'image' => $articleData['imageUrl'],
                                'url' => $articleData['url'],
                                // Add other fields here
                            ]
                        );

                    }
                    // Fetch newly inserted news
                    $news = News::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data fetched successfully',
                        'categories' => $news
                    ]);
                    // Return paginated news
                    //return response()->json($news);
                } else {
                    // If unsuccessful, return an error response
                    return response()->json(['error' => 'Failed to fetch news from Perigon API'], 500);
                }
                //End of Perigon API Response
            } 
            else 
            {
                $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&size=50&q=$countryName");
                //Start of News Data.io API Response
                if ($response->successful()) {
                    $articles = $response->json()['results'];
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['category'][0])) {
                            $firstCategoryName = $articleData['category'][0];
                        }
                        $country = implode(',', $articleData['country']);
                        //$country = implode(',', $articleData['creator']);
                        //old code method
                        /*$news = News::create([
                            'cetegory_id' => $firstCategoryName, // Assuming the category ID is
                            'title' => $articleData['title'],
                            'country' => $country, // Assuming country is an array
                            'auther' => $articleData['creator'][0] ?? 'N/A', // Assuming this field exists in the response
                            'publish' => $articleData['pubDate'],
                            'description' => $articleData['description'] ?? 'N/A',
                            'content' => $articleData['content'] ?? 'N/A',
                            'image' => $articleData['image_url'] ?? 'N/A',
                            'url' => $articleData['link'] ?? 'N/A',
                        ]);*/
                        //new method for create update
                        News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'cetegory_id' => $firstCategoryName,
                                'title' => $articleData['title'],
                                'country' => $country,
                                'auther' => $articleData['creator'][0] ?? 'N/A',
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'] ?? 'N/A',
                                'content' => $articleData['content'] ?? 'N/A',
                                'image' => $articleData['image_url'] ?? 'N/A',
                                'url' => $articleData['link'] ?? 'N/A',
                                // Add other fields here
                            ]
                        );
                    }
                    $news = News::where('title', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data fetched successfully',
                        'categories' => $news
                    ]);
                }
            }
            //End of Perigon API Response
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'categories' => $news
            ]);
        }
        //return response()->json($news);
    }
    //Done Integration of Both API
    public function country(Request $request)
    {
/*      $query = $request->input('query');
        $lngCountryName = $request->input('country');
        // Search for news related to the country
        $news = News::where('country', 'like', "%$query%")->orderBy('created_at', 'desc')->paginate(15);*/
        //new code on the base of long country name search
        $query = $request->input('query');
        $shortCountryName = $request->input('query');
        $longCountryName = $request->input('long_country');
        // Start building the query
        $newsQuery = News::orderBy('created_at', 'desc');
        // Check if the short country name is provided
        if ($shortCountryName) {
            $newsQuery->where('country', 'like', "%$shortCountryName%");
        }
        // Check if the long country name is provided
        if ($longCountryName) {
            $newsQuery->orWhere('country', 'like', "%$longCountryName%");
        }
        // Search for news related to the query parameter
        $news = $newsQuery->where('country', 'like', "%$query%")->paginate(15);
        //end of new code for country based search
        // Check if news were found
        if ($news->isEmpty()) {
            // If no news found, fetch news from the Perigon API
            $countryName = urlencode($query);
            $api = ApiSelect::where('status', 1)->first();
            $apiKey = urlencode($api->api_key);
            if ($api->api_name === 'Perigon') {
                //Start of Perigon API Response
                $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&country=$countryName&size=50&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
                // Check if the response is successful
                if ($response->successful()) {
                    $articles = $response->json()['articles'];
                    // Store fetched news in the database
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['categories'][0]['name'])) {
                            $firstCategoryName = $articleData['categories'][0]['name'];
                        }
                        //old method for add news
                        /*   News::create([
                            'cetegory_id' => $firstCategoryName, // Assuming the category ID is
                            'title' => $articleData['title'],
                            'country' => $articleData['country'],
                            'auther' => $articleData['authorsByline'],
                            'publish' => $articleData['pubDate'],
                            'description' => $articleData['description'],
                            'content' => $articleData['content'],
                            'image' => $articleData['imageUrl'],
                            'url' => $articleData['url'],
                            // Add other fields here
                        ]);*/
                        //new method to add update news
                        News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'cetegory_id' => $firstCategoryName,
                                'title' => $articleData['title'],
                                'country' => $articleData['country'],
                                'auther' => $articleData['authorsByline'],
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'],
                                'content' => $articleData['content'],
                                'image' => $articleData['imageUrl'],
                                'url' => $articleData['url'],
                                // Add other fields here
                            ]
                        );

                        
                    }
                    // Fetch newly inserted news
                    //$news = News::where('country', 'like', "%$query%")->orderBy('created_at', 'desc')->paginate(15);
                    /*Start of new query*/
                    // Start building the query
                    $newsQuery = News::orderBy('created_at', 'desc');
                    // Check if the short country name is provided
                    if ($shortCountryName) {
                        $newsQuery->where('country', 'like', "%$shortCountryName%");
                    }
                    // Check if the long country name is provided
                    if ($longCountryName) {
                        $newsQuery->orWhere('country', 'like', "%$longCountryName%");
                    }
                    // Search for news related to the query parameter
                    $news = $newsQuery->where('country', 'like', "%$query%")->paginate(15);
                    /*End of new query for news*/
                    return response()->json([
                        'status' => true,
                        'message' => 'Categories fetched successfully',
                        'categories' => $news
                    ]);
                    // Return paginated news
                    //return response()->json($news);
                } else {
                    // If unsuccessful, return an error response
                    return response()->json(['error' => 'Failed to fetch news from Perigon API'], 500);
                }
                //End of Perigon API Response
            } else {
                $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&size=50&country=$countryName");
                //Start of News Data.io API Response
                if ($response->successful()) {
                    $articles = $response->json()['results'];
                    foreach ($articles as $articleData) {
                        $firstCategoryName = 'N/A';
                        if (isset($articleData['category'][0])) {
                            $firstCategoryName = $articleData['category'][0];
                        }
                        $country = implode(',', $articleData['country']);
                        //$country = implode(',', $articleData['creator']);
                        //old add method
                        // $news = News::create([
                        //     'cetegory_id' => $firstCategoryName, // Assuming the category ID is
                        //     'title' => $articleData['title'],
                        //     'country' => $countryName, // Assuming country is an array
                        //     'auther' => $articleData['creator'][0] ?? 'N/A', // Assuming this field exists in the response
                        //     'publish' => $articleData['pubDate'],
                        //     'description' => $articleData['description'] ?? 'N/A',
                        //     'content' => $articleData['content'] ?? 'N/A',
                        //     'image' => $articleData['image_url'] ?? 'N/A',
                        //     'url' => $articleData['link'] ?? 'N/A',
                        // ]);
                        //new add update method
                        $news = News::updateOrCreate(
                            ['title' => $articleData['title']],
                            [
                                'category_id' => $firstCategoryName,
                                'country' => $countryName,
                                'auther' => $articleData['creator'][0] ?? 'N/A',
                                'publish' => $articleData['pubDate'],
                                'description' => $articleData['description'] ?? 'N/A',
                                'content' => $articleData['content'] ?? 'N/A',
                                'image' => $articleData['image_url'] ?? 'N/A',
                                'url' => $articleData['link'] ?? 'N/A',
                            ]
                        );

                    } // Fetch newly inserted news
                    //old query for country based search
                    //$news = News::where('country', 'like', "%$query%")->orderBy('created_at', 'desc')->paginate(15);
                    /*Start of new query*/
                    // Start building the query
                    $newsQuery = News::orderBy('created_at', 'desc');
                    // Check if the short country name is provided
                    if ($shortCountryName) {
                        $newsQuery->where('country', 'like', "%$shortCountryName%");
                    }
                    // Check if the long country name is provided
                    if ($longCountryName) {
                        $newsQuery->orWhere('country', 'like', "%$longCountryName%");
                    }
                    // Search for news related to the query parameter
                    $news = $newsQuery->where('country', 'like', "%$query%")->paginate(15);
                    /*End of new query for news*/
                    return response()->json([
                        'status' => true,
                        'message' => 'Categories fetched successfully',
                        'categories' => $news
                    ]);
                }
                //End of News Data.io API Response
            }
        } else {
            // If news found, r
            return response()->json([
                'status' => true,
                'message' => 'Categories fetched successfully',
                'categories' => $news
            ]); //eturn paginated news
            //return response()->json($news);
        }
    }
        public function latest()
    {
        $news = News::latest()->take(5)->get();
        return response()->json([
            'status' => true,
            'message' => 'News fetched successfully',
            'categories' => $news
        ]);
        //return response()->json($news);
    }
}