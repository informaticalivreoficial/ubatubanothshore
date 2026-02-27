<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class GithubUpdates extends Component
{
    public $commits = [];

    public function mount()
    {
        $this->loadCommits();
    }

    public function loadCommits()
    {
        $repoUser = config('services.github.user');
        $repoName = config('services.github.repo');

        if (!$repoUser || !$repoName) {
            logger('GitHub repo config missing');
            return;
        }

        $response = Http::withToken(config('services.github.token'))
            ->timeout(10)
            ->get("https://api.github.com/repos/{$repoUser}/{$repoName}/commits");

        if ($response->successful()) {
            $this->commits = collect($response->json())
                ->take(5)
                ->toArray();
        } else {
            logger('GitHub API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.github-updates');
    }
}
