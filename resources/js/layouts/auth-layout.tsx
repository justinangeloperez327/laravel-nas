import type { PropsWithChildren } from 'react';

type Props = PropsWithChildren<{
    title: string;
    description?: string;
}>;

export default function AuthLayout({ title, description, children }: Props) {
    return (
        <main className="flex min-h-screen items-center justify-center bg-slate-50 px-6 py-12 text-slate-950">
            <section className="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <div className="mb-8">
                    <p className="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">
                        Noor Al Sahara
                    </p>
                    <h1 className="mt-3 text-2xl font-semibold tracking-tight">
                        {title}
                    </h1>
                    {description && (
                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            {description}
                        </p>
                    )}
                </div>

                {children}
            </section>
        </main>
    );
}
