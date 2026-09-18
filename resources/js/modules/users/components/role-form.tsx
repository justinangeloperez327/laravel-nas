import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import type { PermissionOption } from '@/modules/users/types';

type Props = {
    mode: 'create' | 'edit';
    permissions: PermissionOption[];
    role?: {
        id: number;
        name: string;
        slug: string;
        description?: string | null;
        permission_ids: number[];
    };
};

export default function RoleForm({ mode, permissions, role }: Props) {
    const form = useForm({
        name: role?.name ?? '',
        slug: role?.slug ?? '',
        description: role?.description ?? '',
        permission_ids: role?.permission_ids ?? [],
    });

    const togglePermission = (permissionId: number) => {
        form.setData(
            'permission_ids',
            form.data.permission_ids.includes(permissionId)
                ? form.data.permission_ids.filter((id) => id !== permissionId)
                : [...form.data.permission_ids, permissionId],
        );
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (mode === 'create') {
            form.post('/administration/roles');
            return;
        }

        form.put(`/administration/roles/${role?.id}`);
    };

    return (
        <form
            onSubmit={submit}
            className="space-y-6 rounded-xl border border-slate-200 bg-white p-6"
        >
            <div className="grid gap-6 md:grid-cols-2">
                <label className="block">
                    <span className="mb-2 block text-sm font-medium">Name</span>
                    <input
                        type="text"
                        required
                        value={form.data.name}
                        onChange={(event) =>
                            form.setData('name', event.target.value)
                        }
                        className="input"
                    />
                    {form.errors.name && (
                        <span className="mt-2 block text-sm text-red-600">
                            {form.errors.name}
                        </span>
                    )}
                </label>

                <label className="block">
                    <span className="mb-2 block text-sm font-medium">Slug</span>
                    <input
                        type="text"
                        required
                        value={form.data.slug}
                        onChange={(event) =>
                            form.setData('slug', event.target.value)
                        }
                        className="input"
                    />
                    {form.errors.slug && (
                        <span className="mt-2 block text-sm text-red-600">
                            {form.errors.slug}
                        </span>
                    )}
                </label>
            </div>

            <label className="block">
                <span className="mb-2 block text-sm font-medium">
                    Description
                </span>
                <textarea
                    rows={3}
                    value={form.data.description ?? ''}
                    onChange={(event) =>
                        form.setData('description', event.target.value)
                    }
                    className="input"
                />
                {form.errors.description && (
                    <span className="mt-2 block text-sm text-red-600">
                        {form.errors.description}
                    </span>
                )}
            </label>

            <div>
                <p className="text-sm font-medium">Permissions</p>
                <div className="mt-3 grid gap-3 md:grid-cols-2">
                    {permissions.map((permission) => (
                        <label
                            key={permission.id}
                            className="flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3 text-sm"
                        >
                            <input
                                type="checkbox"
                                className="mt-1"
                                checked={form.data.permission_ids.includes(
                                    permission.id,
                                )}
                                onChange={() =>
                                    togglePermission(permission.id)
                                }
                            />
                            <span>
                                <span className="block font-medium">
                                    {permission.name}
                                </span>
                                <span className="text-xs text-slate-500">
                                    {permission.slug}
                                </span>
                            </span>
                        </label>
                    ))}
                </div>
                {form.errors.permission_ids && (
                    <p className="mt-2 text-sm text-red-600">
                        {form.errors.permission_ids}
                    </p>
                )}
            </div>

            <div className="flex justify-end">
                <button
                    type="submit"
                    disabled={form.processing}
                    className="rounded-lg bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {form.processing
                        ? 'Saving…'
                        : mode === 'create'
                          ? 'Create role'
                          : 'Save changes'}
                </button>
            </div>
        </form>
    );
}
