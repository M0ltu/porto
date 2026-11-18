<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()->route('messages.create')->with('success', 'Terima kasih! Kesan Anda menunggu persetujuan admin.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'relation' => ['required', 'in:teman,guru'],
            'body' => ['required', 'string', 'max:500'],
            'ratings' => ['required', 'array'],
            'ratings.design' => ['required', 'integer', 'min:1', 'max:5'],
            'ratings.navigation' => ['required', 'integer', 'min:1', 'max:5'],
            'ratings.content' => ['required', 'integer', 'min:1', 'max:5'],
            'ratings.comfort' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        Message::query()->create([
            'name' => $validated['name'],
            'role' => $validated['relation'] === 'guru' ? 'Guru' : 'Teman sekelas',
            'email' => null,
            'relation' => $validated['relation'],
            'subject' => null,
            'body' => $validated['body'],
            'ratings' => [
                'design' => (int) $validated['ratings']['design'],
                'navigation' => (int) $validated['ratings']['navigation'],
                'content' => (int) $validated['ratings']['content'],
                'comfort' => (int) $validated['ratings']['comfort'],
            ],
            'is_read' => false,
            'approved' => false,
        ]);

        return redirect()
            ->route('messages.create')
            ->with('success', 'Terima kasih! Kesan Anda menunggu persetujuan admin.');
    }
}
