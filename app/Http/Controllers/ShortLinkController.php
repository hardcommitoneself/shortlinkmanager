<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortLink;
use App\Models\Website;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

class ShortLinkController extends Controller
{
    public function create(Request $request)
    {
        $apiKey = $request->query('api');
        $url = $request->query('url');
        $alias = $request->query('alias');

        $request->validate([
            'api' => 'required|string',
            'url' => 'required|url',
            'alias' => 'nullable|string|max:50',
        ]);

        // check if the api key is validate
        if(!Website::where('api_key', $apiKey)->exists()) {
            return response()->json([
                'error'=> 'invalid api key'
            ], 401);
        }

        // check the url is related to the proper website
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? null;

        $website = Website::where('api_key', $apiKey)->first();
        $websiteHost = parse_url($website->url)['host'] ?? null;

        if($host !== $websiteHost) {
            return response()->json([
                'error'=> 'unmatched url'
            ], 401);
        }

        do {
            $shortUrl = Str::random(6);
        } while (ShortLink::where('short_url', $shortUrl)->exists());

        // ShortLink::create([
        //     'short_url' => $shortUrl,
        //     'original_url' => $request->original_url,
        // ]);

        return response()->json([
            'status' => 'success',
            'shortenedUrl' => config('app.url') . '/r/' . $shortUrl
        ], 201);
    }

    public function redirect($shortUrl)
    {
        // Find the original URL based on the short URL
        $link = ShortLink::where('short_url', $shortUrl)->first();

        if ($link) {
            // Redirect to the original URL
            return redirect($link->original_url);
        }

        return response()->json(['message' => 'Not Found'], 404);
    }
}
