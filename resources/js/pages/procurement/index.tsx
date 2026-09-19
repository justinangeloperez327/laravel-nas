import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Project = { id: number; name: string; project_number: string };
type Item = { id: number; description: string; quantity: string; unit: string; estimated_total: string };
type PurchaseRequest = {
    id: number; request_number: string; request_date: string; total_estimated_amount: string;
    currency: string; status: string; project: Project; requester: { name: string }; items: Item[];
};

export default function ProcurementIndex({ purchaseRequests, projects }: { purchaseRequests: PurchaseRequest[]; projects: Project[] }) {
    const { auth } = usePage<SharedProps>().props;
    const permissions = auth.user?.permissions ?? [];
    const canCreate = permissions.includes('purchase-requests.create');
    const canApprove = permissions.includes('purchase-requests.approve');
    const [projectId, setProjectId] = useState('');
    const [number, setNumber] = useState('');
    const [description, setDescription] = useState('');
    const [quantity, setQuantity] = useState('1');
    const [unit, setUnit] = useState('ea');
    const [price, setPrice] = useState('0');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/procurement/purchase-requests', {
            project_id: Number(projectId),
            request_number: number,
            request_date: new Date().toISOString().slice(0, 10),
            required_by_date: null,
            purpose: null,
            currency: 'AED',
            items: [{ description, quantity: Number(quantity), unit, estimated_unit_price: Number(price) }],
        }, { preserveScroll: true });
    };

    return <AppLayout title="Procurement">
        <Head title="Procurement" />
        <div className="space-y-6">
            {canCreate && <section className="rounded-xl border border-slate-200 bg-white p-5">
                <h2 className="font-semibold">Create Purchase Request</h2>
                <form onSubmit={submit} className="mt-4 grid gap-3 lg:grid-cols-3">
                    <select className="input" required value={projectId} onChange={e => setProjectId(e.target.value)}>
                        <option value="">Project</option>{projects.map(p => <option key={p.id} value={p.id}>{p.project_number} — {p.name}</option>)}
                    </select>
                    <input className="input" required placeholder="Purchase Request number" value={number} onChange={e => setNumber(e.target.value)} />
                    <input className="input" required placeholder="Item description" value={description} onChange={e => setDescription(e.target.value)} />
                    <input className="input" type="number" min="0.0001" step="0.0001" value={quantity} onChange={e => setQuantity(e.target.value)} />
                    <input className="input" required placeholder="Unit" value={unit} onChange={e => setUnit(e.target.value)} />
                    <input className="input" type="number" min="0" step="0.01" value={price} onChange={e => setPrice(e.target.value)} />
                    <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white lg:col-span-3" type="submit">Create Purchase Request</button>
                </form>
            </section>}
            <section className="space-y-4">
                {purchaseRequests.map(pr => <article key={pr.id} className="rounded-xl border border-slate-200 bg-white p-5">
                    <div className="flex items-start justify-between gap-4">
                        <div><div className="font-semibold">{pr.request_number}</div><div className="text-sm text-slate-500">{pr.project.name} · {pr.requester.name}</div></div>
                        <div className="text-right"><div className="font-medium">{pr.currency} {pr.total_estimated_amount}</div><div className="text-sm capitalize">{pr.status}</div></div>
                    </div>
                    <div className="mt-4 flex gap-3">
                        {canCreate && pr.status === 'draft' && <button className="text-sm font-medium" onClick={() => router.post(`/procurement/purchase-requests/${pr.id}/submit`)}>Submit</button>}
                        {canApprove && pr.status === 'pending-approval' && <button className="text-sm font-medium" onClick={() => router.post(`/procurement/purchase-requests/${pr.id}/decision`, { action: 'approved', comments: null })}>Approve</button>}
                        {canApprove && pr.status === 'pending-approval' && <button className="text-sm font-medium text-red-700" onClick={() => router.post(`/procurement/purchase-requests/${pr.id}/decision`, { action: 'rejected', comments: null })}>Reject</button>}
                    </div>
                </article>)}
            </section>
        </div>
    </AppLayout>;
}
