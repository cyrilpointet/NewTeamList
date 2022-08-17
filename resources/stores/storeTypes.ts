export interface User {
  id: string;
  name: string;
  email: string;
  createdAt: string;
  updatedAt: string;
}

export interface Membership {
  id: string;
  manager: boolean;
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
  fromTeam: boolean;
  createdAt: string;
  updatedAt: string;
  email: string;
}

export interface TeamMember extends Membership {
  user: User;
}

export interface UserMembership extends Membership {
  team: Team;
}

export interface UserInvitation extends Invitation {
  team: Team;
}

export interface TeamInvitation extends Invitation {
  user: User;
}
