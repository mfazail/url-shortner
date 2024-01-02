<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\UrlAnalytic;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{

    function show(String $url)
    {
        // find the url in the database
        $u = Url::where('short_url', $url)->first();
        // if the url is not found, return 404 not found
        if (!$u) {
            abort(404);
        }

        $agent = new Agent();
        $ip = request()->ip();
        $browser = $agent->browser();
        $device = $agent->platform();
        $platform = $agent->platform();
        $langs = $agent->languages();
        $referer = request()->header('referer');

        // add the url analytic
        UrlAnalytic::create([
            'url_id' => $u->id,
            'ip_address' => $ip,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'referer' => $referer,
            'country' => implode(',', $langs),
        ]);
        // else redirect to the url
        return redirect($u->url)->setStatusCode(302);
        // return view('welcome');
    }
}
