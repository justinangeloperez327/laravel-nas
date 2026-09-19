import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Company = {
    id: number;
    name: string;
    code: string;
    legal_name?: string | null;
    is_active: boolean;
};

type Parent = { id: number; name: string };

type MasterRecord = {
    id: number;
    name: string;
    code: string;
    is_active: boolean;
    company?: Parent;
    business_unit?: Parent;
    department?: Parent;
};

type Props = {
    companies: Company[];
    businessUnits: MasterRecord[];
    departments: MasterRecord[];
    sections: MasterRecord[];
    positions: MasterRecord[];
    locations: MasterRecord[];
    costCenters: MasterRecord[];
};

type ParentOption = {
    id: number;
    name: string;
};

function MasterSection({
    title,
    endpoint,
    records,
    parentKey,
    parentLabel,
    parentOptions = [],
    canCreate,
    canChangeStatus,
}: {
    title: string;
    endpoint: string;
    records: MasterRecord[];
    parentKey?: string;
    parentLabel?: string;
    parentOptions?: ParentOption[];
    canCreate: boolean;
    canChangeStatus: boolean;
}) {
    const [name, setName] = useState('');
    const [code, setCode] = useState('');
    const [parentId, setParentId] = useState('');

    const submit = (event: FormEvent) => {
        event.preventDefault();

        const payload: Record<string, string | boolean | number> = {
            name,
            code,
            is_active: true,
        };

        if (parentKey) {
            payload[parentKey] = Number(parentId);
        }

        router.post(endpoint, payload, {
            preserveScroll: true,
            onSuccess: () => {
                setName('');
                setCode('');
                setParentId('');
            },
        });
    };

    return (
        <section className="rounded-xl border border-slate-200 bg-white p-5">
            <h2 className="text-lg font-semibold">{title}</h2>

            {canCreate && (
                <form
                    onSubmit={submit}
                    className="mt-4 grid gap-3 md:grid-cols-4"
                >
                    {parentKey && (
                        <select
                            required
                            value={parentId}
                            onChange={(event) => setParentId(event.target.value)}
                            className="input"
                        >
                            <option value="">Select {parentLabel}</option>
                            {parentOptions.map((option) => (
                                <option key={option.id} value={option.id}>
                                    {option.name}
                                </option>
                            ))}
                        </select>
                    )}

                    <input
                        required
                        value={name}
                        onChange={(event) => setName(event.target.value)}
                        placeholder="Name"
                        className="input"
                    />

                    <input
                        required
                        value={code}
                        onChange={(event) => setCode(event.target.value)}
                        placeholder="Code"
                        className="input"
                    />

                    <button
                        type="submit"
                        className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white"
                    >
                        Add
                    </button>
                </form>
            )}

            <div className="mt-5 overflow-x-auto">
                <table className="min-w-full divide-y divide-slate-200 text-sm">
                    <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th className="px-3 py-2">Code</th>
                            <th className="px-3 py-2">Name</th>
                            <th className="px-3 py-2">Parent</th>
                            <th className="px-3 py-2">Status</th>
                            <th className="px-3 py-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                        {records.map((record) => (
                            <tr key={record.id}>
                                <td className="px-3 py-3 font-mono text-xs">
                                    {record.code}
                                </td>
                                <td className="px-3 py-3 font-medium">
                                    {record.name}
                                </td>
                                <td className="px-3 py-3 text-slate-600">
                                    {record.company?.name ??
                                        record.business_unit?.name ??
                                        record.department?.name ??
                                        '—'}
                                </td>
                                <td className="px-3 py-3">
                                    {record.is_active ? 'Active' : 'Inactive'}
                                </td>
                                <td className="px-3 py-3 text-right">
                                    {canChangeStatus && (
                                        <button
                                            type="button"
                                            onClick={() =>
                                                router.patch(
                                                    `${endpoint}/${record.id}/status`,
                                                    {
                                                        is_active:
                                                            !record.is_active,
                                                    },
                                                    { preserveScroll: true },
                                                )
                                            }
                                            className="font-medium text-slate-700"
                                        >
                                            {record.is_active
                                                ? 'Deactivate'
                                                : 'Activate'}
                                        </button>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </section>
    );
}

export default function OrganizationIndex({
    companies,
    businessUnits,
    departments,
    sections,
    positions,
    locations,
    costCenters,
}: Props) {
    const { auth } = usePage<SharedProps>().props;
    const permissions = auth.user?.permissions ?? [];
    const canCreate = permissions.includes('organization.create');
    const canChangeStatus = permissions.includes(
        'organization.change-status',
    );

    const companyOptions = companies.map(({ id, name }) => ({ id, name }));
    const businessUnitOptions = businessUnits.map(({ id, name }) => ({
        id,
        name,
    }));
    const departmentOptions = departments.map(({ id, name }) => ({
        id,
        name,
    }));

    return (
        <AppLayout title="Organization">
            <Head title="Organization" />

            <div className="space-y-6">
                <MasterSection
                    title="Companies"
                    endpoint="/administration/organization/companies"
                    records={companies}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Business Units"
                    endpoint="/administration/organization/business-units"
                    records={businessUnits}
                    parentKey="company_id"
                    parentLabel="company"
                    parentOptions={companyOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Departments"
                    endpoint="/administration/organization/departments"
                    records={departments}
                    parentKey="business_unit_id"
                    parentLabel="business unit"
                    parentOptions={businessUnitOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Sections"
                    endpoint="/administration/organization/sections"
                    records={sections}
                    parentKey="department_id"
                    parentLabel="department"
                    parentOptions={departmentOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Positions"
                    endpoint="/administration/organization/positions"
                    records={positions}
                    parentKey="company_id"
                    parentLabel="company"
                    parentOptions={companyOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Locations"
                    endpoint="/administration/organization/locations"
                    records={locations}
                    parentKey="company_id"
                    parentLabel="company"
                    parentOptions={companyOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />

                <MasterSection
                    title="Cost Centers"
                    endpoint="/administration/organization/cost-centers"
                    records={costCenters}
                    parentKey="company_id"
                    parentLabel="company"
                    parentOptions={companyOptions}
                    canCreate={canCreate}
                    canChangeStatus={canChangeStatus}
                />
            </div>
        </AppLayout>
    );
}
