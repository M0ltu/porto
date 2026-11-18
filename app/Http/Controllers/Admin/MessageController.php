<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function toggleApprove(Message $message): RedirectResponse
    {
        $message->update(['approved' => ! $message->approved]);

        return redirect()
            ->back()
            ->with('success', $message->approved
                ? 'Testimoni disetujui dan tampil di publik.'
                : 'Testimoni disembunyikan dari halaman publik.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->back()
            ->with('success', 'Testimoni dihapus.');
    }
}
