<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\Visit;
use App\Models\Website;
use App\Models\WebsiteShortenerSetting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class ShortLinkController extends Controller
{
    public function view(string $shortUrl)
    {
        return view('redirect', [
            'shortUrl' => formatFinalShortenedUrl($shortUrl)
        ]);
    }

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
        // $parsedUrl = parse_url($url);
        // $host = $parsedUrl['host'] ?? null;

        // $websiteHost = parse_url($website->url)['host'] ?? null;

        // if (! areHostsEqual($host, $websiteHost)) {
        //     return response()->json([
        //         'error' => 'unmatched url',
        //     ], 401);
        // }

        $website = Website::where('api_key', $apiKey)->first();

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

    public function redirect(Request $request, $shortUrl)
    {
        // Find the original URL based on the short URL
        $shortLink = ShortLink::where('short_url', $shortUrl)->first();
        $website = $shortLink->website;
        $websiteShortenerSettings = WebsiteShortenerSetting::where('website_id', $website->id)
            ->where('status', 1)
            ->get();

        $isUnvisitedShortenerExist = false;

        // client IP
        $ip = $request->ip();
        $location = Location::get('69.197.184.114');

        // check if the request contains token
        if ($request->has('token')) {
            $visitQuery = Visit::where('token', $request->get('token'));

            if ($visitQuery->exists()) {
                $visit = $visitQuery->get();

                // check ip is same
                if ($visit->ip === $ip) {
                    $visitQuery->update([
                        'is_completed' => true,
                    ]);

                    return redirect($shortLink->original_url);
                } else {
                    return response()->json(['error' => 'Unmatched IP'], 500);
                }
            }
        }

        // if the request does not contain token, then proceed the normal redirect funcationality
        // if there is no website shortener settings, then just redirect user to the original url
        foreach ($websiteShortenerSettings as $key => $websiteShortenerSetting) {
            $countOfVisits = $websiteShortenerSetting->count_visits;

            $views = $websiteShortenerSetting->shortener_setting->views;

            if ($countOfVisits > $views) {
                continue;
            }

            $isUnvisitedShortenerExist = true;

            $token = Str::orderedUuid();

            $shortenerAPILink = $websiteShortenerSetting->shortener_setting->shortener->api_link;
            $shortenerAPIKey = $websiteShortenerSetting->shortener_setting->api_key;
            $shortenerUrl = formatFinalShortenedUrl($shortUrl).'?token='.$token;

            $finalAPILink = str_replace(['{apikey}', '{url}'], [$shortenerAPIKey, $shortenerUrl], $shortenerAPILink);

            try {
                $response = Http::get($finalAPILink);

                if ($response->successful()) {
                    $data = $response->json();

                    // increase the view count & save
                    $websiteShortenerSetting->count_visits++;
                    $websiteShortenerSetting->save();

                    // create new visit
                    $visit = new Visit;

                    $visit->ip = $ip;
                    $visit->country = $location->countryName;
                    $visit->country_code = $location->countryCode;
                    $visit->region = $location->regionName;
                    $visit->city = $location->cityName;
                    $visit->zip = $location->zipCode;
                    $visit->time_zone = $location->timezone;
                    $visit->token = $token;
                    $visit->short_link_id = $shortLink->id;

                    $visit->save();

                    // check the result and redirect user to the shortened url
                    return redirect($data['shortenedUrl']);
                } else {
                    return response()->json(['error' => 'Failed to fetch data'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage(), 'line' => $e->getLine()], 500);
            }
        }

        // if the user visits all shorteners he setup for this website, then we will redirect the user to the original url.
        if (! $isUnvisitedShortenerExist) {
            $originalUrl = $shortLink->original_url;

            return redirect($originalUrl);
        }
    }
}
