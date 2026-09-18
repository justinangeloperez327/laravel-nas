import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import UserForm from '@/modules/users/components/user-form';
import type { RoleOption } from '@/modules/users/types';

type Props = {
    user: {
        id: number;
        name: string;
        email: string;
        is_active: boolean;
        role_ids: number[];
    };
    roles: RoleOption[];
};

export default function EditUser({ user, roles }: Props) {
    return (
        <AppLayout title="Edit user">
            <Head title="Edit user" />

            <div className="mb-5">
                <Link
                    href="/administration/users"
                    className="text-sm font-medium text-slate-600 hover:text-slate-950"
                >
                    ← Back to users
                </Link>
            </div>

            <UserForm mode="edit" roles={roles} user={user} />
        </AppLayout>
    );
}
