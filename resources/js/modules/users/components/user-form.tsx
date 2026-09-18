import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import type { RoleOption } from '@/modules/users/types';

type UserInput = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role_ids: number[];
    is_active?: boolean;
};

type Props = {
    mode: 'create' | 'edit';
    roles: RoleOption[];
    user?: {
        id: number;
        name: string;
        email: string;
        role_ids: number[];
    };
};

export default function UserForm({ mode, roles, user }: Props) {
    const form = useForm<UserInput>({
        name: user?.name ?? '',
        email: user?.email ?? '',
        password: '',
        password_confirmation: '',
        role_ids: user?.role_ids ?? [],
        ...(mode === 'create' ? { is_active: true } : {}),
    });

    const toggleRole = (roleId: number) => {
        form.setData(
            'role_ids',
            form.data.role_ids.includes(roleId)
                ? form.data.role_ids.filter((id) => id !== roleId)
                : [...form.data.role_ids, roleId],
        );
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        if (mode === 'create') {
            form.post('/administration/users');
            return;
        }

        form.put(`/administration/users/${user?.id}`);
    };

    return (
        <form
            onSubmit={submit}
            className="space-y-6 rounded-xl border border-slate-200 bg-white p-6"
        >
            <div className="grid gap-6 md:grid-cols-2">
                <Field label="Name" error={form.errors.name}>
                    <input
                        type="text"
                        required
                        value={form.data.name}
                        onChange={(event) =>
                            form.setData('name', event.target.value)
                        }
                        className="input"
                    />
                </Field>

                <Field label="Email address" error={form.errors.email}>
                    <input
                        type="email"
                        required
                        value={form.data.email}
                        onChange={(event) =>
                            form.setData('email', event.target.value)
                        }
                        className="input"
                    />
                </Field>

                <Field
                    label={mode === 'create' ? 'Password' : 'New password'}
                    error={form.errors.password}
                >
                    <input
                        type="password"
                        required={mode === 'create'}
                        value={form.data.password}
                        onChange={(event) =>
                            form.setData('password', event.target.value)
                        }
                        className="input"
                    />
                </Field>

                <Field
                    label="Confirm password"
                    error={form.errors.password_confirmation}
                >
                    <input
                        type="password"
                        required={mode === 'create'}
                        value={form.data.password_confirmation}
                        onChange={(event) =>
                            form.setData(
                                'password_confirmation',
                                event.target.value,
                            )
                        }
                        className="input"
                    />
                </Field>
            </div>

            <div>
                <p className="text-sm font-medium">Roles</p>
                <div className="mt-3 grid gap-3 sm:grid-cols-2">
                    {roles.map((role) => (
                        <label
                            key={role.id}
                            className="flex items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 text-sm"
                        >
                            <input
                                type="checkbox"
                                checked={form.data.role_ids.includes(role.id)}
                                onChange={() => toggleRole(role.id)}
                            />
                            <span>
                                <span className="block font-medium">
                                    {role.name}
                                </span>
                                <span className="text-xs text-slate-500">
                                    {role.slug}
                                </span>
                            </span>
                        </label>
                    ))}
                </div>
                {form.errors.role_ids && (
                    <p className="mt-2 text-sm text-red-600">
                        {form.errors.role_ids}
                    </p>
                )}
            </div>

            {mode === 'create' && (
                <label className="flex items-center gap-3 text-sm">
                    <input
                        type="checkbox"
                        checked={Boolean(form.data.is_active)}
                        onChange={(event) =>
                            form.setData('is_active', event.target.checked)
                        }
                    />
                    Account is active
                </label>
            )}

            <div className="flex justify-end">
                <button
                    type="submit"
                    disabled={form.processing}
                    className="rounded-lg bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
                >
                    {form.processing
                        ? 'Saving…'
                        : mode === 'create'
                          ? 'Create user'
                          : 'Save changes'}
                </button>
            </div>
        </form>
    );
}

function Field({
    label,
    error,
    children,
}: {
    label: string;
    error?: string;
    children: React.ReactNode;
}) {
    return (
        <label className="block">
            <span className="mb-2 block text-sm font-medium">{label}</span>
            {children}
            {error && <span className="mt-2 block text-sm text-red-600">{error}</span>}
        </label>
    );
}
