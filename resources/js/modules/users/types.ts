export type RoleOption = {
    id: number;
    name: string;
    slug: string;
};

export type PermissionOption = {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
};

export type UserRecord = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    is_active: boolean;
    last_login_at: string | null;
    roles: RoleOption[];
};

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type UserPaginator = {
    data: UserRecord[];
    current_page: number;
    last_page: number;
    links: PaginationLink[];
    total: number;
};

export type RoleRecord = {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    users_count: number;
    permissions_count: number;
    can_edit: boolean;
};
