import { Head } from '@inertiajs/react';

export default function Welcome() {
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
                            Laravel 13 modular monolith foundation.
                        </p>
                    </div>
                </div>
            </main>
        </>
    );
}
