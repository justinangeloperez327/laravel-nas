import { Head, router, usePage } from '@inertiajs/react';
import { useMemo, useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Option = { id: number; name: string };
type ChildOption = Option & { company_id: number };
type Project = {
    id: number;
    project_number: string;
    name: string;
    status: string;
    progress_percentage: string;
    client: Option;
    company: Option;
    business_unit?: Option | null;
    location?: Option | null;
};

export default function ProjectsIndex({
    projects, clients, companies, businessUnits, locations,
}: {
    projects: Project[];
    clients: Option[];
    companies: Option[];
    businessUnits: ChildOption[];
    locations: ChildOption[];
}) {
    const { auth } = usePage<SharedProps>().props;
    const canCreate = auth.user?.permissions.includes('projects.create') ?? false;
    const [clientId, setClientId] = useState('');
    const [companyId, setCompanyId] = useState('');
    const [businessUnitId, setBusinessUnitId] = useState('');
    const [locationId, setLocationId] = useState('');
    const [projectNumber, setProjectNumber] = useState('');
    const [name, setName] = useState('');

    const filteredBusinessUnits = useMemo(() => businessUnits.filter(x => x.company_id === Number(companyId)), [businessUnits, companyId]);
    const filteredLocations = useMemo(() => locations.filter(x => x.company_id === Number(companyId)), [locations, companyId]);

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/projects', {
            client_id: Number(clientId),
            company_id: Number(companyId),
            business_unit_id: businessUnitId ? Number(businessUnitId) : null,
            location_id: locationId ? Number(locationId) : null,
            project_number: projectNumber,
            name,
            description: null,
            status: 'planned',
            start_date: null,
            planned_completion_date: null,
            actual_completion_date: null,
            contract_value: null,
            progress_percentage: 0,
        }, { preserveScroll: true });
    };

    return (
        <AppLayout title="Projects">
            <Head title="Projects" />
            <div className="space-y-6">
                {canCreate && (
                    <section className="rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="font-semibold">Create project</h2>
                        <form onSubmit={submit} className="mt-4 grid gap-3 lg:grid-cols-3">
                            <select className="input" required value={clientId} onChange={e => setClientId(e.target.value)}>
                                <option value="">Select client</option>
                                {clients.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <select className="input" required value={companyId} onChange={e => { setCompanyId(e.target.value); setBusinessUnitId(''); setLocationId(''); }}>
                                <option value="">Select company</option>
                                {companies.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <input className="input" required placeholder="Project number" value={projectNumber} onChange={e => setProjectNumber(e.target.value)} />
                            <input className="input" required placeholder="Project name" value={name} onChange={e => setName(e.target.value)} />
                            <select className="input" value={businessUnitId} onChange={e => setBusinessUnitId(e.target.value)}>
                                <option value="">Business unit (optional)</option>
                                {filteredBusinessUnits.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <select className="input" value={locationId} onChange={e => setLocationId(e.target.value)}>
                                <option value="">Location (optional)</option>
                                {filteredLocations.map(x => <option key={x.id} value={x.id}>{x.name}</option>)}
                            </select>
                            <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white lg:col-span-3" type="submit">Create project</button>
                        </form>
                    </section>
                )}
                <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table className="min-w-full divide-y divide-slate-200 text-sm">
                        <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr><th className="px-4 py-3">Project</th><th className="px-4 py-3">Client</th><th className="px-4 py-3">Company</th><th className="px-4 py-3">Status</th><th className="px-4 py-3">Progress</th></tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {projects.map(project => (
                                <tr key={project.id}>
                                    <td className="px-4 py-4"><div className="font-medium">{project.name}</div><div className="text-xs text-slate-500">{project.project_number}</div></td>
                                    <td className="px-4 py-4">{project.client.name}</td>
                                    <td className="px-4 py-4">{project.company.name}</td>
                                    <td className="px-4 py-4 capitalize">{project.status}</td>
                                    <td className="px-4 py-4">{project.progress_percentage}%</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </section>
            </div>
        </AppLayout>
    );
}
