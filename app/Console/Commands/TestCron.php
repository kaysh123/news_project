<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Api\ApiSelect;
use App\Models\News\News;
use App\Models\Cetegory\Cetegory;
class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:custom-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send custom reminders to users';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        
        $categories = Cetegory::all();
        $categoryTitles = $categories->pluck('title');

        $categoryTitles->each(function ($title) {
            \Log::info('Category Title: ' . $title);

            $countryName = urlencode($title);
            $enabledApis = ApiSelect::where('status', 1)->get();
            foreach ($enabledApis as $api) {
                if ($api->api_name === 'Perigon') {
                    $apiKey = urlencode($api->api_key);
                    if ($title == "Headlines") {
                        $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=none&q=$countryName&size=50&apiKey=$apiKey");
                    } else if ($title == "Crime") {
                        $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&&category=none&q=$countryName&size=50&apiKey=$apiKey");
                    } else {
                        $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=$countryName&size=50&apiKey=$apiKey");
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

                            // Assuming you have a `news` table and a corresponding `News` model
                            // $news = News::create([
                            //     'cetegory_id' => $countryName, // Assuming the category ID is
                            //     'title' => $articleData['title'],
                            //     'country' => $articleData['country'],
                            //     'auther' => $articleData['authorsByline'],
                            //     'publish' => $articleData['pubDate'],
                            //     'description' => $articleData['description'],
                            //     'content' => $articleData['content'],
                            //     'image' => $articleData['imageUrl'],
                            //     'url' => $articleData['url'],
                            // ]);
                             $news = News::updateOrCreate(
                                [
                                    'title' => $articleData['title']
                                ],
                                [
                                    'cetegory_id' => $countryName,
                                    'country' => $articleData['country'],
                                    'auther' => $articleData['creator'][0] ?? 'N/A',
                                    'publish' => $articleData['pubDate'],
                                    'description' => $articleData['description'] ?? 'N/A',
                                    'content' => $articleData['content'] ?? 'N/A',
                                    'image' => $articleData['image_url'] ?? 'N/A',
                                    'url' => $articleData['link'] ?? 'N/A'
                                ]
                            );

                            // Delete news articles that are 3 days old
                            $threeDaysAgo = now()->subDays(2);
                            News::where('created_at', '<', $threeDaysAgo)->delete();
                        }
                    }
                }  else if($api->api_name === 'News Data') {
                      $apiKey = urlencode($api->api_key);
                    if ($title == "Headlines") {
                        $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&q=$countryName&country=us&size=50");
                    } else if ($title == "Crime") {
                        $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&q=$countryName&country=us&size=50");
                    } else {
                        $response = Http::get("https://newsdata.io/api/1/news?apikey=$apiKey&language=en&category=$countryName&country=us&size=50");
                    }
                    if ($response->successful()) {
                        $articles = $response->json()['results'];
                        foreach ($articles as $articleData) {
                            $firstCategoryName = 'N/A';
                            if (isset($articleData['category'][0]['name'])) {
                                $firstCategoryName = $articleData['category'][0]['name'];
                            }
                            $country = implode(',', $articleData['country']);
                            //$country = implode(',', $articleData['creator']);
                                // $news = News::create([
                                //     'cetegory_id' => $countryName, // Assuming the category ID is
                                //     'title' => $articleData['title'],
                                //     'country' => $country, // Assuming country is an array
                                //     'auther' => $articleData['creator'][0]?? 'N/A', // Assuming this field exists in the response
                                //     'publish' => $articleData['pubDate'],
                                //     'description' => $articleData['description'] ?? 'N/A',
                                //     'content' => $articleData['content'] ?? 'N/A',
                                //     'image' => $articleData['image_url'] ?? 'N/A',
                                //     'url' => $articleData['link'] ?? 'N/A',
                                // ]);
                                $news = News::updateOrCreate(
                                    [
                                        'title' => $articleData['title']
                                    ],
                                    [
                                        'cetegory_id' => $countryName,
                                        'country' => $country,
                                        'auther' => $articleData['creator'][0] ?? 'N/A',
                                        'publish' => $articleData['pubDate'],
                                        'description' => $articleData['description'] ?? 'N/A',
                                        'content' => $articleData['content'] ?? 'N/A',
                                        'image' => $articleData['image_url'] ?? 'N/A',
                                        'url' => $articleData['link'] ?? 'N/A'
                                    ]
                                );
                            $threeDaysAgo = now()->subDays(2);
                             \Log::info('Deleting Data for One Day: ' . $threeDaysAgo);
                            News::where('created_at', '<', $threeDaysAgo)->delete();
                        }
                    }
                }
            }
        });
    }
    //old working handle
    public function Oldhandle()
    {
        $categories = Cetegory::all();
        $categoryTitles = $categories->pluck('title');

        $categoryTitles->each(function ($title) {
            \Log::info('Category Title: ' . $title);

            $countryName = urlencode($title);

            if ($title == "Headlines") {
                $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=none&q=$countryName&size=100&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
            } else if ($title == "Crime") {
                $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&&category=none&q=$countryName&size=100&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
            } else {
                $response = Http::get("https://api.goperigon.com/v1/all?showReprints=false&sortBy=date&language=en&category=$countryName&size=100&apiKey=a1c29d01-d6d1-4996-aa11-6012d65d79c8");
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

                    // Assuming you have a `news` table and a corresponding `News` model
                    $news = News::create([
                        'cetegory_id' => $countryName, // Assuming the category ID is
                        'title' => $articleData['title'],
                        'country' => $articleData['country'],
                        'auther' => $articleData['authorsByline'],
                        'publish' => $articleData['pubDate'],
                        'description' => $articleData['description'],
                        'content' => $articleData['content'],
                        'image' => $articleData['imageUrl'],
                        'url' => $articleData['url'],
                    ]);

                    // Delete news articles that are 3 days old
                    $threeDaysAgo = now()->subDays(2);
                    News::where('created_at', '<', $threeDaysAgo)->delete();
                }
            }
        });
    }
}
