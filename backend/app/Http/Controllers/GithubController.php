<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController
{
    public function redirect()
    {
        return Socialite::driver('github')->scopes([])->redirect();
    }

    public function callback(Request $request)
    {
         try {
            $ghUser = Socialite::driver('github')->user();
        } catch (\Throwable $e) {
            return redirect()->route('home')
                ->with('error', 'Η σύνδεση απέτυχε: ' . $e->getMessage());
        }

        $user = $request->user();

        $linkedElsewhere = User::where('github_id', $ghUser->getId())
            ->whereKeyNot($user->getKey())
            ->exists();

        if ($linkedElsewhere) {
            return redirect()->route('home')
                ->with('error', 'Αυτός ο λογαριασμός GitHub είναι ήδη συνδεδεμένος με άλλον χρήστη.');
        }

        $user->update([
            'github_id'            => $ghUser->getId(),
            'github_name'          => $ghUser->getName() ?: $ghUser->getNickname(),
            'login'                => $ghUser->getNickname(),
            'avatar_url'           => $ghUser->getAvatar(),
            'github_email'         => $ghUser->getEmail(),
            'github_token'         => $ghUser->token,
            'github_refresh_token' => $ghUser->refreshToken,
            'token_expires_at'     => now()->addSeconds($ghUser->expiresIn ?? 28800),
        ]);

        return redirect()->away('https://github.com/apps/review-apps-apo/installations/new');
    }

    /**
     * Αποσυνδέει μόνο τον λογαριασμό GitHub — η συνεδρία της εφαρμογής παραμένει ενεργή.
     */
    public function disconnect(Request $request)
    {
        $request->user()->update([
            'github_id'            => null,
            'github_name'          => null,
            'login'                => null,
            'avatar_url'           => null,
            'github_email'         => null,
            'github_token'         => null,
            'github_refresh_token' => null,
            'token_expires_at'     => null,
        ]);

        return redirect()->route('home')
            ->with('status', 'Ο λογαριασμός GitHub αποσυνδέθηκε.');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:200'],
        ]);

        $user  = $request->user();
        $query = $validated['q'];

        if (! $user?->github_id) {
            return response()->json(['message' => 'Χρειάζεται σύνδεση με GitHub.'], 401);
        }

        $query .= ' user:' . $user->login;

        $client = Http::withHeaders(['X-GitHub-Api-Version' => '2022-11-28'])
            ->acceptJson()
            ->timeout(15)
            ->baseUrl('https://api.github.com');

        if ($user->github_token) {
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