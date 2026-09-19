import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

export default function Dashboard() {
    return (
        <AppLayout title="Dashboard">
            <Head title="Dashboard" />

            <div className="rounded-xl border border-slate-200 bg-white p-6">
                <h2 className="font-semibold">System foundation ready</h2>
                <p className="mt-2 text-sm leading-6 text-slate-600">
                    User access management is the first business module in the
                    Noor Al Sahara modular monolith.
                </p>
            </div>
        </AppLayout>
    );
}
