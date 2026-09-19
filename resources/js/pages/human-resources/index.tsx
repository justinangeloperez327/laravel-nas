import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Option = { id: number; name: string };
type Employee = {
    id: number; employee_number: string; first_name: string; last_name: string; status: string;
    company: Option; department?: Option | null; position?: Option | null;
};

export default function HumanResourcesIndex({ employees, companies }: { employees: Employee[]; companies: Option[] }) {
    const { auth } = usePage<SharedProps>().props;
    const canManage = auth.user?.permissions.includes('human-resources.manage') ?? false;
    const [companyId, setCompanyId] = useState('');
    const [number, setNumber] = useState('');
    const [firstName, setFirstName] = useState('');
    const [lastName, setLastName] = useState('');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/human-resources/employees', {
            user_id: null, company_id: Number(companyId), department_id: null, position_id: null,
            employee_number: number, first_name: firstName, last_name: lastName,
            email: null, phone: null, nationality: null, hire_date: null,
            employment_type: null, status: 'active',
        }, { preserveScroll: true });
    };

    return <AppLayout title="Human Resources">
        <Head title="Human Resources" />
        <div className="space-y-6">
            {canManage && <section className="rounded-xl border border-slate-200 bg-white p-5">
                <h2 className="font-semibold">Add employee</h2>
                <form onSubmit={submit} className="mt-4 grid gap-3 md:grid-cols-4">
                    <select className="input" required value={companyId} onChange={e => setCompanyId(e.target.value)}>
                        <option value="">Company</option>{companies.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                    <input className="input" required placeholder="Employee number" value={number} onChange={e => setNumber(e.target.value)} />
                    <input className="input" required placeholder="First name" value={firstName} onChange={e => setFirstName(e.target.value)} />
                    <input className="input" required placeholder="Last name" value={lastName} onChange={e => setLastName(e.target.value)} />
                    <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white md:col-span-4" type="submit">Add employee</button>
                </form>
            </section>}
            <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table className="min-w-full divide-y divide-slate-200 text-sm">
                    <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th className="px-4 py-3">Employee</th><th className="px-4 py-3">Company</th><th className="px-4 py-3">Department</th><th className="px-4 py-3">Position</th><th className="px-4 py-3">Status</th></tr></thead>
                    <tbody className="divide-y divide-slate-100">{employees.map(e => <tr key={e.id}><td className="px-4 py-4"><div className="font-medium">{e.first_name} {e.last_name}</div><div className="text-xs text-slate-500">{e.employee_number}</div></td><td className="px-4 py-4">{e.company.name}</td><td className="px-4 py-4">{e.department?.name ?? '—'}</td><td className="px-4 py-4">{e.position?.name ?? '—'}</td><td className="px-4 py-4 capitalize">{e.status}</td></tr>)}</tbody>
                </table>
            </section>
        </div>
    </AppLayout>;
}
