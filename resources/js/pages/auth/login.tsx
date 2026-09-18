import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/auth-layout';

type Props = {
    status?: string;
    canResetPassword: boolean;
};

export default function Login({ status, canResetPassword }: Props) {
    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.post('/login', {
            onFinish: () => form.reset('password'),
        });
    };

    return (
        <AuthLayout
            title="Log in"
            description="Use your Noor Al Sahara system account."
        >
            <Head title="Log in" />

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
                        autoComplete="email"
                        autoFocus
                        required
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

                <div>
                    <div className="mb-2 flex items-center justify-between">
                        <label
                            htmlFor="password"
                            className="block text-sm font-medium"
                        >
                            Password
                        </label>
                        {canResetPassword && (
                            <Link
                                href="/forgot-password"
                                className="text-sm font-medium text-slate-600 hover:text-slate-950"
                            >
                                Forgot password?
                            </Link>
                        )}
                    </div>
                    <input
                        id="password"
                        type="password"
                        autoComplete="current-password"
                        required
                        value={form.data.password}
                        onChange={(event) =>
                            form.setData('password', event.target.value)
                        }
                        className="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-500"
                    />
                    {form.errors.password && (
                        <p className="mt-2 text-sm text-red-600">
                            {form.errors.password}
                        </p>
                    )}
                </div>

                <label className="flex items-center gap-3 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        checked={form.data.remember}
                        onChange={(event) =>
                            form.setData('remember', event.target.checked)
                        }
                        className="size-4 rounded border-slate-300"
                    />
                    Remember me
                </label>

                <button
                    type="submit"
                    disabled={form.processing}
                    className="w-full rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {form.processing ? 'Logging in…' : 'Log in'}
                </button>
            </form>
        </AuthLayout>
    );
}
