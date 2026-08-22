<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController
{
    public function redirect()
    {
        return Socialite::driver('github')->scopes([])->redirect();
    }

    public function callback()
    {
         try {
            $ghUser = Socialite::driver('github')->user();
        } catch (\Throwable $e) {
            return redirect()->route('home')
                ->with('error', 'Η σύνδεση απέτυχε: ' . $e->getMessage());
        }

        $user = User::updateOrCreate(
            ['github_id' => $ghUser->getId()],
            [
                'github_name'          => $ghUser->getName() ?: $ghUser->getNickname(),
                'login'                => $ghUser->getNickname(),
                'avatar_url'           => $ghUser->getAvatar(),
                'github_email'         => $ghUser->getEmail(),
                'github_token'         => $ghUser->token,
                'github_refresh_token' => $ghUser->refreshToken,
                'token_expires_at'     => now()->addSeconds($ghUser->expiresIn ?? 28800),
            ]
        );

        return redirect()->away('https://github.com/apps/review-apps-apo/installations/new');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:200'],
        ]);

        $user  = Auth::user();
        $query = $validated['q'];

        if (! $user) {
            return response()->json(['message' => 'Χρειάζεται σύνδεση με GitHub.'], 401);
        }

        $query .= ' user:' . $user->login;

        $client = Http::withHeaders(['X-GitHub-Api-Version' => '2022-11-28'])
            ->acceptJson()
            ->timeout(15)
            ->baseUrl('https://api.github.com');

        if ($user?->github_token) {
            $client = $client->withToken($user->github_token);
        }

        $response = $client->get('/search/repositories', [
            'q'        => $query,
            'per_page' => 20,
            'sort'     => 'best-match',
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Το GitHub API απέτυχε.',
            ], $response->status());
        }

        $items = collect($response->json('items', []))->map(fn (array $repo) => [
            'full_name'   => $repo['full_name'],
            'description' => $repo['description'],
            'url'         => $repo['html_url'],
            'language'    => $repo['language'],
            'stars'       => $repo['stargazers_count'],
            'private'     => $repo['private'],
            'updated_at'  => $repo['updated_at'],
        ]);

        return response()->json([
            'total' => $response->json('total_count', 0),
            'items' => $items,
        ]);
    }
}