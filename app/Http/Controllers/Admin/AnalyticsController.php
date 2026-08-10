<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        [$start,$end]=$this->period($request); $base=$this->base($start,$end);
        return view('admin.analytics.index',[
            'start'=>$start,'end'=>$end,
            'totalViews'=>(clone $base)->count(),
            'uniqueVisitors'=>(clone $base)->distinct()->count('visitor_hash'),
            'topPages'=>(clone $base)->selectRaw('path, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')->groupBy('path')->orderByDesc('views')->limit(20)->get(),
            'daily'=>(clone $base)->selectRaw('visited_on, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')->groupBy('visited_on')->orderBy('visited_on')->get(),
            'sources'=>(clone $base)->whereNotNull('referrer_host')->selectRaw('referrer_host, COUNT(*) as views')->groupBy('referrer_host')->orderByDesc('views')->limit(15)->get(),
            'campaigns'=>(clone $base)->whereNotNull('utm_source')->selectRaw('utm_source, utm_medium, utm_campaign, COUNT(*) as views')->groupBy('utm_source','utm_medium','utm_campaign')->orderByDesc('views')->limit(20)->get(),
            'devices'=>(clone $base)->selectRaw('device_type, COUNT(*) as views')->groupBy('device_type')->orderByDesc('views')->get(),
            'countries'=>(clone $base)->whereNotNull('country_code')->selectRaw('country_code, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')->groupBy('country_code')->orderByDesc('views')->limit(30)->get(),
            'cities'=>(clone $base)->whereNotNull('city')->selectRaw('country_code, region, city, COUNT(*) as views')->groupBy('country_code','region','city')->orderByDesc('views')->limit(30)->get(),
            'browsers'=>(clone $base)->selectRaw('browser, COUNT(*) as views')->groupBy('browser')->orderByDesc('views')->get(),
            'operatingSystems'=>(clone $base)->selectRaw('operating_system, COUNT(*) as views')->groupBy('operating_system')->orderByDesc('views')->get(),
            'reachTypes'=>(clone $base)->selectRaw('reach_type, COUNT(*) as views')->groupBy('reach_type')->orderByDesc('views')->get(),
            'organizations'=>(clone $base)->whereNotNull('organization')->selectRaw('organization, COUNT(*) as views')->groupBy('organization')->orderByDesc('views')->limit(20)->get(),
            'recentVisits'=>(clone $base)->latest('created_at')->limit(100)->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$start,$end]=$this->period($request);
        return response()->streamDownload(function () use($start,$end): void {
            $out=fopen('php://output','wb'); fputcsv($out,['Date','Path','Views','Unique visitors']);
            $rows=$this->base($start,$end)->selectRaw('visited_on, path, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')->groupBy('visited_on','path')->orderBy('visited_on')->get();
            foreach($rows as $row) fputcsv($out,[$row->visited_on,$row->path,$row->views,$row->visitors]);
            fclose($out);
        },"analytics-{$start->toDateString()}-{$end->toDateString()}.csv",['Content-Type'=>'text/csv']);
    }

    private function period(Request $request): array
    {
        $data=$request->validate(['start'=>['nullable','date'],'end'=>['nullable','date','after_or_equal:start']]);
        $end=isset($data['end'])?CarbonImmutable::parse($data['end'])->endOfDay():CarbonImmutable::today()->endOfDay();
        $start=isset($data['start'])?CarbonImmutable::parse($data['start'])->startOfDay():$end->subDays(29)->startOfDay();
        if ($start->diffInDays($end)>365) $start=$end->subDays(365)->startOfDay();
        return [$start,$end];
    }
    private function base(CarbonImmutable $start,CarbonImmutable $end): Builder
    {
        return PageVisit::query()->whereBetween('created_at',[$start,$end]);
    }
}
