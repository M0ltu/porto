<x-guest-layout title="Verifikasi email" heading="Verifikasi email Anda" subheading="Klik tautan yang kami kirim ke email Anda untuk mengaktifkan akun.">
    <p class="text-sm leading-6 text-ink-muted">
        Terima kasih sudah mendaftar. Sebelum melanjutkan, verifikasi email lewat tautan yang baru kami kirim. Jika belum menerima, kami bisa mengirim ulang.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 rounded-xl border border-success bg-success-soft px-3.5 py-2.5 text-sm font-medium text-success" role="status">
            Tautan verifikasi baru sudah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-5 flex flex-col gap-3 border-t border-line pt-5 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Kirim ulang email verifikasi</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-ink-muted underline-offset-4 hover:text-ink hover:underline focus:outline-none">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
