<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Page;
use App\Models\PageVisit;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VisitTracker
{
    public function __construct(private readonly VisitorContextResolver $context) {}

    public function record(Request $request): void
    {
        $agent=(string)$request->userAgent();
        if ($this->isBot($agent)) return;
        $path='/'.ltrim($request->path(),'/');
        if ($path==='//') $path='/';
        $hash=hash_hmac('sha256', implode('|',[$request->ip(),$agent,now()->toDateString(),$request->getHost()]), (string)config('app.key'));
        $cooldown=max(1,(int)config('analytics.deduplication_minutes',30));
        if (!Cache::add('analytics:view:'.hash('sha256',$hash.'|'.$path),true,now()->addMinutes($cooldown))) return;

        [$type,$id]=$this->content($request);
        $referrer=parse_url((string)$request->headers->get('referer'),PHP_URL_HOST);
        PageVisit::create([
            'visited_on'=>today(), 'path'=>Str::limit($path,500,''), 'route_name'=>$request->route()?->getName(),
            'content_type'=>$type, 'content_id'=>$id, 'visitor_hash'=>$hash,
            'referrer_host'=>$referrer?Str::limit(strtolower((string)$referrer),255,''):null,
            'utm_source'=>$this->campaign($request,'utm_source',120), 'utm_medium'=>$this->campaign($request,'utm_medium',120),
            'utm_campaign'=>$this->campaign($request,'utm_campaign',180), 'device_type'=>$this->device($agent),
            ...$this->context->resolve($request),
        ]);
        if ($type==='post' && $id) Post::whereKey($id)->increment('views');
    }

    private function content(Request $request): array
    {
        return match($request->route()?->getName()) {
            'home'=>SiteSetting::valueOf('homepage_id') ? ['page',(int)SiteSetting::valueOf('homepage_id')] : ['home',null], 'posts.index'=>['blog',null],
            'page.show'=>['page',Page::where('slug',$request->route('slug'))->value('id')],
            'post.show'=>['post',$request->route('post') instanceof Post?$request->route('post')->id:null],
            'category.show'=>['category',$request->route('category') instanceof Category?$request->route('category')->id:null],
            default=>['other',null],
        };
    }

    private function campaign(Request $request,string $key,int $max): ?string
    {
        $value=$request->query($key); return is_string($value)&&$value!==''?Str::limit(strip_tags($value),$max,''):null;
    }
    private function device(string $agent): string
    {
        if (preg_match('/ipad|tablet|kindle/i',$agent)) return 'tablet';
        if (preg_match('/mobile|iphone|android/i',$agent)) return 'mobile';
        return 'desktop';
    }
    private function isBot(string $agent): bool
    {
        return $agent==='' || (bool)preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit|headless|lighthouse|monitor/i',$agent);
    }
}
