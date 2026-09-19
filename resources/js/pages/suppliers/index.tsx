import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Category = { id: number; name: string };
type Supplier = { id: number; name: string; code: string; status: string; trade_license_expiry?: string | null; categories: Category[] };

export default function SuppliersIndex({ suppliers, categories }: { suppliers: Supplier[]; categories: Category[] }) {
    const { auth } = usePage<SharedProps>().props;
    const permissions = auth.user?.permissions ?? [];
    const canCreate = permissions.includes('suppliers.create');
    const canApprove = permissions.includes('suppliers.approve');
    const [name, setName] = useState('');
    const [code, setCode] = useState('');
    const [categoryId, setCategoryId] = useState('');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/suppliers', {
            name, code, legal_name: null, trade_license_number: null, trade_license_expiry: null,
            tax_registration_number: null, email: null, phone: null, website: null,
            status: 'pending', category_ids: categoryId ? [Number(categoryId)] : [],
        }, { preserveScroll: true });
    };

    return <AppLayout title="Suppliers">
        <Head title="Suppliers" />
        <div className="space-y-6">
            {canCreate && <section className="rounded-xl border border-slate-200 bg-white p-5">
                <h2 className="font-semibold">Add supplier</h2>
                <form onSubmit={submit} className="mt-4 grid gap-3 md:grid-cols-4">
                    <input className="input" required placeholder="Supplier name" value={name} onChange={e => setName(e.target.value)} />
                    <input className="input" required placeholder="Code" value={code} onChange={e => setCode(e.target.value)} />
                    <select className="input" value={categoryId} onChange={e => setCategoryId(e.target.value)}>
                        <option value="">No category</option>{categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white" type="submit">Add supplier</button>
                </form>
            </section>}
            <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table className="min-w-full divide-y divide-slate-200 text-sm">
                    <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr><th className="px-4 py-3">Supplier</th><th className="px-4 py-3">Categories</th><th className="px-4 py-3">Status</th><th className="px-4 py-3 text-right">Action</th></tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">{suppliers.map(supplier => <tr key={supplier.id}>
                        <td className="px-4 py-4"><div className="font-medium">{supplier.name}</div><div className="text-xs text-slate-500">{supplier.code}</div></td>
                        <td className="px-4 py-4">{supplier.categories.map(c => c.name).join(', ') || '—'}</td>
                        <td className="px-4 py-4 capitalize">{supplier.status}</td>
                        <td className="px-4 py-4 text-right">{canApprove && supplier.status === 'pending' && <button className="font-medium" onClick={() => router.post(`/suppliers/${supplier.id}/approve`)}>Approve</button>}</td>
                    </tr>)}</tbody>
                </table>
            </section>
        </div>
    </AppLayout>;
}
