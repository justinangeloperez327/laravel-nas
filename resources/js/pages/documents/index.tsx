import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Revision = { id: number; revision_code: string; status: string; original_filename?: string | null };
type Document = { id: number; document_number: string; title: string; document_type: string; discipline?: string | null; revisions: Revision[]; project?: { name: string } | null };
type Project = { id: number; name: string; project_number: string };

export default function DocumentsIndex({ documents, projects }: { documents: Document[]; projects: Project[] }) {
    const { auth } = usePage<SharedProps>().props;
    const permissions = auth.user?.permissions ?? [];
    const canCreate = permissions.includes('documents.create');
    const canSubmit = permissions.includes('documents.submit');
    const canApprove = permissions.includes('documents.approve');
    const [projectId, setProjectId] = useState('');
    const [number, setNumber] = useState('');
    const [title, setTitle] = useState('');
    const [type, setType] = useState('');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/documents', {
            project_id: projectId ? Number(projectId) : null,
            contract_id: null,
            document_number: number,
            title,
            document_type: type,
            discipline: null,
            status: 'active',
        }, { preserveScroll: true });
    };

    return (
        <AppLayout title="Document Control">
            <Head title="Document Control" />
            <div className="space-y-6">
                {canCreate && <section className="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 className="font-semibold">Register document</h2>
                    <form onSubmit={submit} className="mt-4 grid gap-3 md:grid-cols-4">
                        <select className="input" value={projectId} onChange={e => setProjectId(e.target.value)}>
                            <option value="">No project</option>{projects.map(p => <option key={p.id} value={p.id}>{p.project_number} — {p.name}</option>)}
                        </select>
                        <input className="input" required placeholder="Document number" value={number} onChange={e => setNumber(e.target.value)} />
                        <input className="input" required placeholder="Title" value={title} onChange={e => setTitle(e.target.value)} />
                        <input className="input" required placeholder="Document type" value={type} onChange={e => setType(e.target.value)} />
                        <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white md:col-span-4" type="submit">Register document</button>
                    </form>
                </section>}
                <section className="space-y-4">
                    {documents.map(document => {
                        const latest = document.revisions[0];
                        return <article key={document.id} className="rounded-xl border border-slate-200 bg-white p-5">
                            <div className="flex items-start justify-between gap-4">
                                <div><div className="font-semibold">{document.title}</div><div className="text-xs text-slate-500">{document.document_number} · {document.document_type}</div></div>
                                <div className="text-sm">{latest ? `Rev ${latest.revision_code} · ${latest.status}` : 'No revision'}</div>
                            </div>
                            {latest && <div className="mt-4 flex gap-3">
                                {canSubmit && latest.status === 'draft' && <button className="text-sm font-medium" onClick={() => router.post(`/documents/revisions/${latest.id}/submit`)}>Submit</button>}
                                {canApprove && latest.status === 'submitted' && <button className="text-sm font-medium" onClick={() => router.post(`/documents/revisions/${latest.id}/decision`, { action: 'approved', comments: null })}>Approve</button>}
                                {canApprove && latest.status === 'submitted' && <button className="text-sm font-medium text-red-700" onClick={() => router.post(`/documents/revisions/${latest.id}/decision`, { action: 'rejected', comments: null })}>Reject</button>}
                            </div>}
                        </article>;
                    })}
                </section>
            </div>
        </AppLayout>
    );
}
