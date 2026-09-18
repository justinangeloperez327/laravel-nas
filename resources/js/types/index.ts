export type AuthUser = {
    id: number;
    name: string;
    email: string;
    roles: string[];
    permissions: string[];
};

export type SharedProps = {
    appName: string;
    auth: {
        user: AuthUser | null;
    };
    flash: {
        success?: string | null;
    };
    errors: Record<string, string>;
};
