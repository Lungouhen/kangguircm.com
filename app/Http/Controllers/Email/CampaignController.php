<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\SubscriberList;
use App\Repositories\CampaignRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignRepository $campaignRepository
    ) {}

    public function index(): View
    {
        $campaigns = $this->campaignRepository->paginate(15);
        return view('email.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $lists = SubscriberList::all();
        return view('email.campaigns.create', compact('lists'));
    }

    public function store(array $data): Campaign
    {
        return $this->campaignRepository->create($data);
    }

    public function send(int $id): RedirectResponse
    {
        $campaign = $this->campaignRepository->find($id);
        
        if (!$campaign) {
            abort(404);
        }
        
        $this->campaignRepository->send($campaign);
        
        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign is being sent.');
    }
}
