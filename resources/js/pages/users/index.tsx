import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';
import type { UserPaginator } from '@/modules/users/types';

type Props = {
    users: UserPaginator;
    filters: {
        search: string;
    };
};

export default function UsersIndex({ users, filters }: Props) {
    const { auth } = usePage<SharedProps>().props;
    const [search, setSearch] = useState(filters.search);

    const can = (permission: string) =>
        auth.user?.permissions.includes(permission) ?? false;

    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            '/administration/users',
            { search },
            { preserveState: true, replace: true },
        );
    };

    const changeStatus = (id: number, active: boolean) => {
        const action = active ? 'activate' : 'deactivate';

        if (! window.confirm(`Are you sure you want to ${action} this user?`)) {
            return;
        }

        router.patch(`/administration/users/${id}/status`, {
            is_active: active,
        });
    };

    return (
        <AppLayout title="Users">
            <Head title="Users" />

            <div className="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <form
                    onSubmit={submitSearch}
                    className="flex w-full max-w-md gap-2"
                >
                    <input
                        type="search"
                        value={search}
                        onChange={(event) => setSearch(event.target.value)}
                        placeholder="Search name or email"
                        className="input"
                    />
                    <button
                        type="submit"
                        className="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium"
                    >
                        Search
                    </button>
                </form>

                {can('users.create') && (
                    <Link
                        href="/administration/users/create"
                        className="rounded-lg bg-slate-950 px-4 py-2.5 text-center text-sm font-semibold text-white"
                    >
                        Create user
                    </Link>
                )}
            </div>

            <div className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-slate-200 text-sm">
                        <thead className="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th className="px-4 py-3">User</th>
                                <th className="px-4 py-3">Roles</th>
                                <th className="px-4 py-3">Status</th>
                                <th className="px-4 py-3">Last login</th>
                                <th className="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {users.data.map((user) => {
                                const canChangeStatus =
                                    can('users.change-status') &&
                                    user.id !== auth.user?.id;

                                return (
                                    <tr key={user.id}>
                                        <td className="px-4 py-4">
                                            <div className="font-medium">
                                                {user.name}
                                            </div>
                                            <div className="text-slate-500">
                                                {user.email}
                                            </div>
                                            {!user.email_verified_at && (
                                                <div className="mt-1 text-xs text-amber-700">
                                                    Email not verified
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-4 py-4">
                                            <div className="flex flex-wrap gap-1.5">
                                                {user.roles.length > 0 ? (
                                                    user.roles.map((role) => (
                                                        <span
                                                            key={role.id}
                                                            className="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium"
                                                        >
                                                            {role.name}
                                                        </span>
                                                    ))
                                                ) : (
                                                    <span className="text-slate-400">
                                                        No role
                                                    </span>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-4 py-4">
                                            <span
                                                className={
                                                    user.is_active
                                                        ? 'text-emerald-700'
                                                        : 'text-slate-500'
                                                }
                                            >
                                                {user.is_active
                                                    ? 'Active'
                                                    : 'Inactive'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-4 text-slate-600">
                                            {user.last_login_at
                                                ? new Date(
                                                      user.last_login_at,
                                                  ).toLocaleString()
                                                : 'Never'}
                                        </td>
                                        <td className="px-4 py-4">
                                            <div className="flex justify-end gap-3">
                                                {can('users.update') && (
                                                    <Link
                                                        href={`/administration/users/${user.id}/edit`}
                                                        className="font-medium text-slate-700 hover:text-slate-950"
                                                    >
                                                        Edit
                                                    </Link>
                                                )}

                                                {canChangeStatus && (
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            changeStatus(
                                                                user.id,
                                                                !user.is_active,
                                                            )
                                                        }
                                                        className="font-medium text-slate-700 hover:text-slate-950"
                                                    >
                                                        {user.is_active
                                                            ? 'Deactivate'
                                                            : 'Activate'}
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })}

                            {users.data.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={5}
                                        className="px-4 py-10 text-center text-slate-500"
                                    >
                                        No users found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {users.last_page > 1 && (
                <div className="mt-5 flex flex-wrap gap-2">
                    {users.links.map((link, index) =>
                        link.url ? (
                            <Link
                                key={index}
                                href={link.url}
                                preserveState
                                className={`rounded-lg border px-3 py-2 text-sm ${
                                    link.active
                                        ? 'border-slate-950 bg-slate-950 text-white'
                                        : 'border-slate-300 bg-white text-slate-700'
                                }`}
                                dangerouslySetInnerHTML={{
                                    __html: link.label,
                                }}
                            />
                        ) : (
                            <span
                                key={index}
                                className="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-400"
                                dangerouslySetInnerHTML={{
                                    __html: link.label,
                                }}
                            />
                        ),
                    )}
                </div>
            )}
        </AppLayout>
    );
}
