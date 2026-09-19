import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import RoleForm from '@/modules/users/components/role-form';
import type { PermissionOption } from '@/modules/users/types';

type Props = {
    role: {
        id: number;
        name: string;
        slug: string;
        description?: string | null;
        permission_ids: number[];
    };
    permissions: PermissionOption[];
};

export default function EditRole({ role, permissions }: Props) {
    return (
        <AppLayout title="Edit role">
            <Head title="Edit role" />

            <div className="mb-5">
                <Link
                    href="/administration/roles"
                    className="text-sm font-medium text-slate-600 hover:text-slate-950"
                >
                    ← Back to roles
                </Link>
            </div>

            <RoleForm mode="edit" role={role} permissions={permissions} />
        </AppLayout>
    );
}
