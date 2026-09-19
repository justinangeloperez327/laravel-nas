import { Head, router } from '@inertiajs/react';
import AuthLayout from '@/layouts/auth-layout';

export default function VerifyEmail({ status }: { status?: string }) {
    return (
        <AuthLayout
            title="Verify your email"
            description="Use the verification link sent to your email before continuing."
        >
            <Head title="Verify email" />

            {status === 'verification-link-sent' && (
                <div className="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    A new verification link has been sent.
                </div>
            )}

            <div className="space-y-3">
                <button
                    type="button"
                    onClick={() =>
                        router.post('/email/verification-notification')
                    }
                    className="w-full rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white"
                >
                    Resend verification email
                </button>

                <button
                    type="button"
                    onClick={() => router.post('/logout')}
                    className="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700"
                >
                    Log out
                </button>
            </div>
        </AuthLayout>
    );
}
