import { Head, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import AuthLayout from '@/layouts/auth-layout';

type Props = {
    token: string;
    email: string;
    passwordRules: string;
};

export default function ResetPassword({
    token,
    email,
    passwordRules,
}: Props) {
    const form = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.post('/reset-password', {
            onFinish: () =>
                form.reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Reset password"
            description="Choose a new password for your account."
        >
            <Head title="Reset password" />

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
                        value={form.data.email}
                        readOnly
                        className="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-600"
                    />
                    {form.errors.email && (
                        <p className="mt-2 text-sm text-red-600">
                            {form.errors.email}
                        </p>
                    )}
                </div>

                <div>
                    <label
                        htmlFor="password"
                        className="mb-2 block text-sm font-medium"
                    >
                        New password
                    </label>
                    <input
                        id="password"
                        type="password"
                        autoComplete="new-password"
                        required
                        autoFocus
                        value={form.data.password}
                        onChange={(event) =>
                            form.setData('password', event.target.value)
                        }
                        aria-describedby="password-rules"
                        className="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-500"
                    />
                    <p
                        id="password-rules"
                        className="mt-2 text-xs text-slate-500"
                    >
                        {passwordRules}
                    </p>
                    {form.errors.password && (
                        <p className="mt-2 text-sm text-red-600">
                            {form.errors.password}
                        </p>
                    )}
                </div>

                <div>
                    <label
                        htmlFor="password_confirmation"
                        className="mb-2 block text-sm font-medium"
                    >
                        Confirm new password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        autoComplete="new-password"
                        required
                        value={form.data.password_confirmation}
                        onChange={(event) =>
                            form.setData(
                                'password_confirmation',
                                event.target.value,
                            )
                        }
                        className="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-slate-500"
                    />
                </div>

                <button
                    type="submit"
                    disabled={form.processing}
                    className="w-full rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {form.processing ? 'Resetting…' : 'Reset password'}
                </button>
            </form>
        </AuthLayout>
    );
}
