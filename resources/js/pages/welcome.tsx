import { Head, Link, usePage } from '@inertiajs/react';
import type { SharedProps } from '@/types';

export default function Welcome() {
    const { auth } = usePage<SharedProps>().props;

    return (
        <>
            <Head title="Welcome" />

            <main className="min-h-screen bg-white text-slate-950">
                <div className="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-16">
                    <div className="max-w-2xl">
                        <p className="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Noor Al Sahara
                        </p>

                        <h1 className="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">
                            Integrated Business System
                        </h1>

                        <p className="mt-6 text-lg leading-8 text-slate-600">
                            Laravel 13 modular monolith for Noor Al Sahara
                            business operations.
                        </p>

                        <div className="mt-8">
                            <Link
                                href={auth.user ? '/dashboard' : '/login'}
                                className="inline-flex rounded-lg bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                            >
                                {auth.user ? 'Open dashboard' : 'Log in'}
                            </Link>
                        </div>
                    </div>
                </div>
            </main>
        </>
    );
}
