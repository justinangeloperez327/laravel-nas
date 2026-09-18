import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import type { RoleRecord } from '@/modules/users/types';
import type { SharedProps } from '@/types';

export default function RolesIndex({ roles }: { roles: RoleRecord[] }) {
    const { auth } = usePage<SharedProps>().props;
    const canCreate =
        auth.user?.permissions.includes('roles.create') ?? false;
    const canUpdate =
        auth.user?.permissions.includes('roles.update') ?? false;

    return (
        <AppLayout title="Roles & Permissions">
            <Head title="Roles & Permissions" />

            <div className="mb-5 flex justify-end">
                {canCreate && (
                    <Link
                        href="/administration/roles/create"
                        className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white"
                    >
                        Create role
                    </Link>
                )}
            </div>

            <div className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table className="min-w-full divide-y divide-slate-200 text-sm">
                    <thead className="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th className="px-4 py-3">Role</th>
                            <th className="px-4 py-3">Users</th>
                            <th className="px-4 py-3">Permissions</th>
                            <th className="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                        {roles.map((role) => (
                            <tr key={role.id}>
                                <td className="px-4 py-4">
                                    <div className="font-medium">
                                        {role.name}
                                    </div>
                                    <div className="text-xs text-slate-500">
                                        {role.slug}
                                    </div>
                                    {role.description && (
                                        <p className="mt-1 text-sm text-slate-600">
                                            {role.description}
                                        </p>
                                    )}
                                </td>
                                <td className="px-4 py-4">
                                    {role.users_count}
                                </td>
                                <td className="px-4 py-4">
                                    {role.permissions_count}
                                </td>
                                <td className="px-4 py-4 text-right">
                                    {canUpdate && (
                                        <Link
                                            href={`/administration/roles/${role.id}/edit`}
                                            className="font-medium text-slate-700 hover:text-slate-950"
                                        >
                                            Edit
                                        </Link>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}
