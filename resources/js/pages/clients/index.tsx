import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Contact = {
    id: number;
    name: string;
    job_title?: string | null;
    email?: string | null;
    mobile?: string | null;
    is_primary: boolean;
};

type Address = {
    id: number;
    type: string;
    line_1: string;
    city?: string | null;
    country?: string | null;
};

type Client = {
    id: number;
    name: string;
    code: string;
    legal_name?: string | null;
    tax_registration_number?: string | null;
    website?: string | null;
    is_active: boolean;
    contacts: Contact[];
    addresses: Address[];
};

export default function ClientsIndex({ clients }: { clients: Client[] }) {
    const { auth } = usePage<SharedProps>().props;
    const permissions = auth.user?.permissions ?? [];
    const canCreate = permissions.includes('clients.create');
    const canUpdate = permissions.includes('clients.update');
    const canChangeStatus = permissions.includes('clients.change-status');

    const [name, setName] = useState('');
    const [code, setCode] = useState('');
    const [legalName, setLegalName] = useState('');
    const [contactClientId, setContactClientId] = useState('');
    const [contactName, setContactName] = useState('');
    const [contactEmail, setContactEmail] = useState('');

    const createClient = (event: FormEvent) => {
        event.preventDefault();
        router.post('/clients', {
            name,
            code,
            legal_name: legalName || null,
            tax_registration_number: null,
            website: null,
            is_active: true,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setName('');
                setCode('');
                setLegalName('');
            },
        });
    };

    const addContact = (event: FormEvent) => {
        event.preventDefault();
        router.post('/clients/contacts', {
            client_id: Number(contactClientId),
            name: contactName,
            job_title: null,
            email: contactEmail || null,
            phone: null,
            mobile: null,
            is_primary: false,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setContactName('');
                setContactEmail('');
            },
        });
    };

    return (
        <AppLayout title="Clients">
            <Head title="Clients" />

            <div className="space-y-6">
                {canCreate && (
                    <section className="rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="font-semibold">Add client</h2>
                        <form onSubmit={createClient} className="mt-4 grid gap-3 md:grid-cols-4">
                            <input className="input" required placeholder="Client name" value={name} onChange={(e) => setName(e.target.value)} />
                            <input className="input" required placeholder="Code" value={code} onChange={(e) => setCode(e.target.value)} />
                            <input className="input" placeholder="Legal name" value={legalName} onChange={(e) => setLegalName(e.target.value)} />
                            <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white" type="submit">Add client</button>
                        </form>
                    </section>
                )}

                {canUpdate && clients.length > 0 && (
                    <section className="rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="font-semibold">Add client contact</h2>
                        <form onSubmit={addContact} className="mt-4 grid gap-3 md:grid-cols-4">
                            <select className="input" required value={contactClientId} onChange={(e) => setContactClientId(e.target.value)}>
                                <option value="">Select client</option>
                                {clients.map((client) => <option key={client.id} value={client.id}>{client.name}</option>)}
                            </select>
                            <input className="input" required placeholder="Contact name" value={contactName} onChange={(e) => setContactName(e.target.value)} />
                            <input className="input" type="email" placeholder="Email" value={contactEmail} onChange={(e) => setContactEmail(e.target.value)} />
                            <button className="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold" type="submit">Add contact</button>
                        </form>
                    </section>
                )}

                <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-slate-200 text-sm">
                            <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th className="px-4 py-3">Client</th>
                                    <th className="px-4 py-3">Contacts</th>
                                    <th className="px-4 py-3">Addresses</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                {clients.map((client) => (
                                    <tr key={client.id}>
                                        <td className="px-4 py-4">
                                            <div className="font-medium">{client.name}</div>
                                            <div className="text-xs text-slate-500">{client.code}</div>
                                        </td>
                                        <td className="px-4 py-4">
                                            {client.contacts.length > 0 ? client.contacts.map((contact) => (
                                                <div key={contact.id}>
                                                    <span className="font-medium">{contact.name}</span>
                                                    {contact.email && <span className="ml-2 text-slate-500">{contact.email}</span>}
                                                </div>
                                            )) : '—'}
                                        </td>
                                        <td className="px-4 py-4">{client.addresses.length}</td>
                                        <td className="px-4 py-4">{client.is_active ? 'Active' : 'Inactive'}</td>
                                        <td className="px-4 py-4 text-right">
                                            {canChangeStatus && (
                                                <button
                                                    type="button"
                                                    className="font-medium text-slate-700"
                                                    onClick={() => router.patch(`/clients/${client.id}/status`, { is_active: !client.is_active }, { preserveScroll: true })}
                                                >
                                                    {client.is_active ? 'Deactivate' : 'Activate'}
                                                </button>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </AppLayout>
    );
}
