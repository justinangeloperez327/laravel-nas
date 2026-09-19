import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Option = { id: number; name: string };
type Contract = {
    id: number;
    contract_number: string;
    title: string;
    currency: string;
    contract_value: string;
    status: string;
    projects_count: number;
    client: Option;
    company: Option;
};

export default function ContractsIndex({ contracts, clients, companies }: { contracts: Contract[]; clients: Option[]; companies: Option[] }) {
    const { auth } = usePage<SharedProps>().props;
    const canCreate = auth.user?.permissions.includes('contracts.create') ?? false;
    const [clientId, setClientId] = useState('');
    const [companyId, setCompanyId] = useState('');
    const [number, setNumber] = useState('');
    const [title, setTitle] = useState('');
    const [value, setValue] = useState('0');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/contracts', {
            client_id: Number(clientId), company_id: Number(companyId),
            contract_number: number, title, scope: null, currency: 'AED',
            contract_value: Number(value), retention_percentage: 0, advance_percentage: 0,
            start_date: null, completion_date: null, status: 'draft', payment_terms: null,
        }, { preserveScroll: true });
    };

    return (
        <AppLayout title="Contracts">
            <Head title="Contracts" />
            <div className="space-y-6">
                {canCreate && (
                    <section className="rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="font-semibold">Create contract</h2>
                        <form onSubmit={submit} className="mt-4 grid gap-3 lg:grid-cols-5">
                            <select className="input" required value={clientId} onChange={e => setClientId(e.target.value)}>
                                <option value="">Client</option>{clients.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <select className="input" required value={companyId} onChange={e => setCompanyId(e.target.value)}>
                                <option value="">Company</option>{companies.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <input className="input" required placeholder="Contract number" value={number} onChange={e => setNumber(e.target.value)} />
                            <input className="input" required placeholder="Title" value={title} onChange={e => setTitle(e.target.value)} />
                            <input className="input" type="number" min="0" step="0.01" value={value} onChange={e => setValue(e.target.value)} />
                            <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white lg:col-span-5" type="submit">Create contract</button>
                        </form>
                    </section>
                )}
                <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table className="min-w-full divide-y divide-slate-200 text-sm">
                        <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr><th className="px-4 py-3">Contract</th><th className="px-4 py-3">Client</th><th className="px-4 py-3">Value</th><th className="px-4 py-3">Projects</th><th className="px-4 py-3">Status</th></tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {contracts.map(contract => (
                                <tr key={contract.id}>
                                    <td className="px-4 py-4"><div className="font-medium">{contract.title}</div><div className="text-xs text-slate-500">{contract.contract_number}</div></td>
                                    <td className="px-4 py-4">{contract.client.name}</td>
                                    <td className="px-4 py-4">{contract.currency} {contract.contract_value}</td>
                                    <td className="px-4 py-4">{contract.projects_count}</td>
                                    <td className="px-4 py-4 capitalize">{contract.status}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </section>
            </div>
        </AppLayout>
    );
}
