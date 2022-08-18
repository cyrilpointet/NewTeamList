export interface User {
    id: string;
    name: string;
    email: string;
    createdAt: string;
    updatedAt: string;
}

export interface Team {
    id: string;
    name: string;
    createdAt: string;
    updatedAt: string;
}

export interface Invitation {
    id: string;
    is_from_team: boolean;
    createdAt: string;
    updatedAt: string;
    user_email: string;
}

export interface UserInvitation extends Invitation {
    team: Team;
}

export interface TeamInvitation extends Invitation {
    user: User;
}
