<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitorContextResolver
{
    public function resolve(Request $request): array
    {
        $agent=(string)$request->userAgent();
        $geo=config('analytics.trust_geo_headers') ? $this->geoHeaders($request) : [];
        return array_merge([
            'browser'=>$this->browser($agent),
            'operating_system'=>$this->os($agent),
            'language'=>$this->language((string)$request->header('Accept-Language')),
            'reach_type'=>$this->reach($request),
        ],$geo);
    }

    private function geoHeaders(Request $request): array
    {
        $headers=config('analytics.geo_headers',[]);
        $country=strtoupper((string)$request->header($headers['country']??'CF-IPCountry'));
        return [
            'country_code'=>preg_match('/^[A-Z]{2}$/',$country)&&$country!=='XX'?$country:null,
            'region'=>$this->text($request->header($headers['region']??'X-Geo-Region'),120),
            'city'=>$this->text($request->header($headers['city']??'X-Geo-City'),120),
            'organization'=>$this->text($request->header($headers['organization']??'X-Geo-Organization'),180),
        ];
    }

    private function browser(string $agent): string
    {
        return match(true) {
            str_contains($agent,'Edg/')=>'Edge', str_contains($agent,'OPR/')||str_contains($agent,'Opera')=>'Opera',
            str_contains($agent,'Chrome/')=>'Chrome', str_contains($agent,'Firefox/')=>'Firefox',
            str_contains($agent,'Safari/')&&!str_contains($agent,'Chrome/')=>'Safari', default=>'Other',
        };
    }
    private function os(string $agent): string
    {
        return match(true) {
            preg_match('/Windows/i',$agent)===1=>'Windows', preg_match('/Android/i',$agent)===1=>'Android',
            preg_match('/iPhone|iPad|iOS/i',$agent)===1=>'iOS', preg_match('/Mac OS|Macintosh/i',$agent)===1=>'macOS',
            preg_match('/Linux/i',$agent)===1=>'Linux', default=>'Other',
        };
    }
    private function language(string $header): ?string
    {
        $value=strtolower(substr(trim(explode(',',$header)[0]??''),0,12));
        return preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/',$value)?$value:null;
    }
    private function reach(Request $request): string
    {
        if ($request->filled('utm_source')) return 'campaign';
        $host=strtolower((string)parse_url((string)$request->header('referer'),PHP_URL_HOST));
        if ($host==='') return 'direct';
        if ($host===$request->getHost()) return 'internal';
        if (preg_match('/google\.|bing\.|yahoo\.|duckduckgo\.|baidu\.|yandex\./',$host)) return 'search';
        if (preg_match('/facebook\.|instagram\.|linkedin\.|x\.com$|twitter\.|youtube\.|tiktok\./',$host)) return 'social';
        return 'referral';
    }
    private function text(mixed $value,int $max): ?string
    {
        if (!is_string($value)||trim($value)==='') return null;
        return Str::limit(preg_replace('/[^\pL\pN .,_-]/u','',trim($value))??'',$max,'') ?: null;
    }
}
