import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import UserForm from '@/modules/users/components/user-form';
import type { RoleOption } from '@/modules/users/types';

export default function CreateUser({ roles }: { roles: RoleOption[] }) {
    return (
        <AppLayout title="Create user">
            <Head title="Create user" />

            <div className="mb-5">
                <Link
                    href="/administration/users"
                    className="text-sm font-medium text-slate-600 hover:text-slate-950"
                >
                    ← Back to users
                </Link>
            </div>

            <UserForm mode="create" roles={roles} />
        </AppLayout>
    );
}
