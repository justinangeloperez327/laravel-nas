import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import RoleForm from '@/modules/users/components/role-form';
import type { PermissionOption } from '@/modules/users/types';

export default function CreateRole({
    permissions,
}: {
    permissions: PermissionOption[];
}) {
    return (
        <AppLayout title="Create role">
            <Head title="Create role" />

            <div className="mb-5">
                <Link
                    href="/administration/roles"
                    className="text-sm font-medium text-slate-600 hover:text-slate-950"
                >
                    ← Back to roles
                </Link>
            </div>

            <RoleForm mode="create" permissions={permissions} />
        </AppLayout>
    );
}
