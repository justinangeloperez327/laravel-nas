import { Link, router, usePage } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';
import type { SharedProps } from '@/types';

type Props = PropsWithChildren<{
    title: string;
}>;

export default function AppLayout({ title, children }: Props) {
    const { auth, flash } = usePage<SharedProps>().props;
    const user = auth.user;

    const can = (permission: string) =>
        user?.permissions.includes(permission) ?? false;

    return (
        <div className="min-h-screen bg-slate-50 text-slate-950">
            <header className="border-b border-slate-200 bg-white">
                <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                    <div>
                        <Link
                            href="/dashboard"
                            className="font-semibold tracking-tight"
                        >
                            Noor Al Sahara
                        </Link>
                        <p className="text-xs text-slate-500">
                            Integrated Business System
                        </p>
                    </div>

                    <div className="flex items-center gap-4 text-sm">
                        <span className="text-slate-600">{user?.name}</span>
                        <button
                            type="button"
                            onClick={() => router.post('/logout')}
                            className="font-medium text-slate-700 hover:text-slate-950"
                        >
                            Log out
                        </button>
                    </div>
                </div>
            </header>

            <div className="mx-auto grid max-w-7xl gap-8 px-6 py-8 lg:grid-cols-[220px_1fr]">
                <aside>
                    <nav className="space-y-1 text-sm">
                        <Link
                            href="/dashboard"
                            className="block rounded-lg px-3 py-2 font-medium hover:bg-white"
                        >
                            Dashboard
                        </Link>

                        {can('inventory.view') && (
                            <Link href="/inventory" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Inventory
                            </Link>
                        )}

                        {can('procurement.view') && (
                            <Link href="/procurement" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Procurement
                            </Link>
                        )}

                        {can('suppliers.view') && (
                            <Link href="/suppliers" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Suppliers
                            </Link>
                        )}

                        {can('documents.view') && (
                            <Link href="/documents" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Document Control
                            </Link>
                        )}

                        {can('approvals.view') && (
                            <Link href="/administration/approvals" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Approvals
                            </Link>
                        )}

                        {can('contracts.view') && (
                            <Link href="/contracts" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Contracts
                            </Link>
                        )}

                        {can('projects.view') && (
                            <Link href="/projects" className="block rounded-lg px-3 py-2 font-medium hover:bg-white">
                                Projects
                            </Link>
                        )}

                        {can('clients.view') && (
                            <Link
                                href="/clients"
                                className="block rounded-lg px-3 py-2 font-medium hover:bg-white"
                            >
                                Clients
                            </Link>
                        )}

                        {can('organization.view') && (
                            <Link
                                href="/administration/organization"
                                className="block rounded-lg px-3 py-2 font-medium hover:bg-white"
                            >
                                Organization
                            </Link>
                        )}

                        {can('users.view') && (
                            <Link
                                href="/administration/users"
                                className="block rounded-lg px-3 py-2 font-medium hover:bg-white"
                            >
                                Users
                            </Link>
                        )}

                        {can('roles.view') && (
                            <Link
                                href="/administration/roles"
                                className="block rounded-lg px-3 py-2 font-medium hover:bg-white"
                            >
                                Roles & Permissions
                            </Link>
                        )}
                    </nav>
                </aside>

                <main>
                    <div className="mb-6 flex items-center justify-between">
                        <h1 className="text-2xl font-semibold tracking-tight">
                            {title}
                        </h1>
                    </div>

                    {flash.success && (
                        <div className="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {flash.success}
                        </div>
                    )}

                    {children}
                </main>
            </div>
        </div>
    );
}
