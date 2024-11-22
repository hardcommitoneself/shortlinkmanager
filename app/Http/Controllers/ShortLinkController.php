<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function create(Request $request)
    {
        $apiKey = $request->query('api');
        $url = $request->query('url');
        $alias = $request->query('alias');

        // check if the api key is validate
        if (! Website::where('api_key', $apiKey)->exists()) {
            return response()->json([
                'error' => 'invalid api key',
            ], 401);
        }

        // check the url is related to the proper website
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? null;

        $website = Website::where('api_key', $apiKey)->first();
        $websiteHost = parse_url($website->url)['host'] ?? null;

        if (! areHostsEqual($host, $websiteHost)) {
            return response()->json([
                'error' => 'unmatched url',
            ], 401);
        }

        do {
            $shortUrl = Str::random(6);
        } while (ShortLink::where('short_url', $shortUrl)->exists());

        $shortLink = new ShortLink;

        $shortLink->website_id = $website->id;
        $shortLink->short_url = $shortUrl;
        $shortLink->original_url = $url;

        $shortLink->save();

        return response()->json([
            'status' => 'success',
            'shortenedUrl' => config('app.url').'/r/'.$shortUrl,
        ], 201);
    }

    public function redirect($shortUrl)
    {
        // Find the original URL based on the short URL
        $shortLink = ShortLink::where('short_url', $shortUrl)->first();
        $website = $shortLink->website;
        $websiteShortenerSettings = $website->websiteShortenerSettings;

        $isUnvisitedShortenerExist = false;

        foreach ($websiteShortenerSettings as $key => $websiteShortenerSetting) {
            $countOfVisits = $websiteShortenerSetting->count_visits;

            $views = $websiteShortenerSetting->shortener_setting->views;

            if ($countOfVisits > $views) {
                continue;
            }

            $isUnvisitedShortenerExist = true;

            $shortenerAPILink = $websiteShortenerSetting->shortener_setting->shortener->api_link;
            $shortenerAPIKey = $websiteShortenerSetting->shortener_setting->api_key;
            $shortenerUrl = $shortLink->original_url;

            $finalAPILink = str_replace(['{apikey}', '{url}'], [$shortenerAPIKey, $shortenerUrl], $shortenerAPILink);

            try {
                $response = Http::get($finalAPILink);

                if ($response->successful()) {
                    $data = $response->json();

                    // increase the view count & save
                    $websiteShortenerSetting->count_visits++;
                    $websiteShortenerSetting->save();

                    // check the result and redirect user to the shortened url
                } elseif ($response->failed()) {
                    return response()->json(['error' => 'Failed to fetch data'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
            }
        }

        // if the user visits all shorteners he setup for this website, then we will redirect the user to the original url.
        if (! $isUnvisitedShortenerExist) {
            $originalUrl = $shortLink->original_url;

            return redirect($originalUrl);
        }
    }
}
