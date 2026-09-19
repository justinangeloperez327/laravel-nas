import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/auth-layout';

export default function ForgotPassword({ status }: { status?: string }) {
    const form = useForm({
        email: '',
    });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        form.post('/forgot-password');
    };

    return (
        <AuthLayout
            title="Forgot password"
            description="Enter your email address to receive a password reset link."
        >
            <Head title="Forgot password" />

            {status && (
                <div className="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <label
                        htmlFor="email"
                        className="mb-2 block text-sm font-medium"
                    >
                        Email address
                    </label>
                    <input
                        id="email"
                        type="email"
                        required
                        autoFocus
                        value={form.data.email}
                        onChange={(event) =>
                            form.setData('email', event.target.value)
                        }
                        className="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-500"
                    />
                    {form.errors.email && (
                        <p className="mt-2 text-sm text-red-600">
                            {form.errors.email}
                        </p>
                    )}
                </div>

                <button
                    type="submit"
                    disabled={form.processing}
                    className="w-full rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {form.processing
                        ? 'Sending…'
                        : 'Send password reset link'}
                </button>
            </form>

            <div className="mt-6 text-center">
                <Link
                    href="/login"
                    className="text-sm font-medium text-slate-600 hover:text-slate-950"
                >
                    Return to login
                </Link>
            </div>
        </AuthLayout>
    );
}
