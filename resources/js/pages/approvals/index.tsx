import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Step = { id: number; sequence: number; name: string; approver_type: string; approver_reference: string };
type Workflow = { id: number; name: string; code: string; entity_type: string; is_active: boolean; steps: Step[] };
type ApprovalRequest = { id: number; entity_type: string; entity_id: number; status: string; current_step_sequence?: number | null; workflow: { name: string }; submitter: { name: string } };

export default function ApprovalsIndex({ workflows, requests }: { workflows: Workflow[]; requests: ApprovalRequest[] }) {
    const { auth } = usePage<SharedProps>().props;
    const canConfigure = auth.user?.permissions.includes('approvals.configure') ?? false;
    const [name, setName] = useState('');
    const [code, setCode] = useState('');
    const [entityType, setEntityType] = useState('');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/administration/approvals/workflows', {
            name, code, entity_type: entityType, description: null, is_active: true,
        }, { preserveScroll: true });
    };

    return (
        <AppLayout title="Approvals">
            <Head title="Approvals" />
            <div className="space-y-6">
                {canConfigure && (
                    <section className="rounded-xl border border-slate-200 bg-white p-5">
                        <h2 className="font-semibold">Create workflow</h2>
                        <form onSubmit={submit} className="mt-4 grid gap-3 md:grid-cols-4">
                            <input className="input" required placeholder="Workflow name" value={name} onChange={e => setName(e.target.value)} />
                            <input className="input" required placeholder="Code" value={code} onChange={e => setCode(e.target.value)} />
                            <input className="input" required placeholder="Entity type" value={entityType} onChange={e => setEntityType(e.target.value)} />
                            <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white" type="submit">Create</button>
                        </form>
                    </section>
                )}
                <section className="grid gap-4 lg:grid-cols-2">
                    {workflows.map(workflow => (
                        <article key={workflow.id} className="rounded-xl border border-slate-200 bg-white p-5">
                            <div className="font-semibold">{workflow.name}</div>
                            <div className="text-xs text-slate-500">{workflow.code} · {workflow.entity_type}</div>
                            <ol className="mt-4 space-y-2 text-sm">
                                {workflow.steps.map(step => <li key={step.id}>{step.sequence}. {step.name} — {step.approver_type}: {step.approver_reference}</li>)}
                            </ol>
                        </article>
                    ))}
                </section>
                <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <table className="min-w-full divide-y divide-slate-200 text-sm">
                        <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr><th className="px-4 py-3">Workflow</th><th className="px-4 py-3">Record</th><th className="px-4 py-3">Submitted by</th><th className="px-4 py-3">Status</th></tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {requests.map(request => <tr key={request.id}>
                                <td className="px-4 py-4">{request.workflow.name}</td>
                                <td className="px-4 py-4">{request.entity_type} #{request.entity_id}</td>
                                <td className="px-4 py-4">{request.submitter.name}</td>
                                <td className="px-4 py-4 capitalize">{request.status}</td>
                            </tr>)}
                        </tbody>
                    </table>
                </section>
            </div>
        </AppLayout>
    );
}
