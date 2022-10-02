<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kutia\Larafirebase\Facades\Larafirebase;

class InvitationController extends Controller
{
    /**
     * Create an invitation from a team
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam email string required an email
     */
    public function createFromTeam(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $test = Invitation::where('team_id', '=', $id)->where('user_email', '=', $request->email)->get();
        if (0 !== count($test)) {
            return response("Une invitation est déjà en cours", 403);
        }

        $user = User::where('email', '=', $request->email)->first();
        if (null !== $user) {
            $teams = $user->teams()->where('team_id', '=', $id)->get();
            if (0 !== count($teams)) {
                return response([
                    'message' => 'User is already a team member'
                ], 403);
            }
        }

        $invitation = Invitation::create([
            'team_id' => $id,
            'user_email' => $request->email,
            'is_from_team' => true
        ]);
        $invitation->team;

        if ($user !== null && $user->device_key !== null) {
            $FcmToken = [$user->device_key];
            Larafirebase::withAdditionalData([
                'item' => 'USER',
                'id' => $user->id,
                'title' => "Invitation Team List",
                'body' => 'Vous avez une nouvelle invitation à une liste partagée dans Team List'
            ])
                ->sendMessage($FcmToken);
        }

        if ($user === null) {
            $sender = $request->user();
            $homeLink = url('/');
            Mail::to($request->email)->send(new InvitationMail([
                "name" => $sender->name,
                "link" => $homeLink
            ]));
        }


        return response($invitation, 201);
    }

    /**
     * Manage an invitation sent to a team by a user
     * @group Team management
     * @urlParam id int required The team's id
     * @bodyParam id int required The invitation's id
     * @bodyParam status boolean required an email
     */
    public function manageUserInvitation(Request $request, $id)
    {
        try {
            $request->validate([
                'id' => 'required',
                'status' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $invitation = Invitation::find($request->id);

        if (null === $invitation) {
            return response([
                "message" => "Unknown invitation"
            ], 404);
        }
        if (intval($id) !== $invitation->team_id) {
            return response([
                "message" => "Invalid band Id"
            ], 403);
        }
        if (false !== $invitation->is_from_team) {
            return response([
                "message" => "Invitation comes not from user"
            ], 403);
        }

        $user = User::firstWhere('email', $invitation->user_email);
        if (null === $user) {
            return response([
                "message" => "Unknown user"
            ], 404);
        }

        $teams = $user->teams()->where('team_id', $request->route('id'))->get();
        if (0 !== count($teams)) {
            $invitation->delete();
            return response([
                'message' => ['User is already a team member']
            ], 403);
        }

        if (true === $request->status) {
            $team->members()->attach($user->id, ['admin' => false]);
            $FcmToken = [];
            foreach ($team->members as $member) {
                if ($member->device_key !== null) {
                    $FcmToken[] = $member->device_key;
                }
            }
            Larafirebase::withTitle('Teamlist: nouveau membre')
                ->withBody($user->name . ' a rejoint ' . $team->name)
                ->withAdditionalData([
                    'item' => 'TEAM',
                    'id' => $team->id,
                ])
                ->sendMessage($FcmToken);
        }
        $invitation->delete();

        $team->members;
        $team->invitations;

        if (true === $request->status && $user->device_key !== null) {
            Larafirebase::withTitle('Teamlist - Invitation acceptée')
                ->withBody('Vous avez rejoint ' . $team->name)
                ->withAdditionalData([
                    'item' => 'USER',
                    'id' => $team->id,
                ])
                ->sendMessage($FcmToken);
        }

        return $team;
    }

    /**
     * Send invitation to a team from a user
     * @group User management
     * @bodyParam id int required The team's id to join
     */
    public function createFromUser(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $user = $request->user();
        $teams = $user->teams()->where('team_id', '=', $request->id)->get();
        if (0 !== count($teams)) {
            return response([
                'message' => 'User is already a member'
            ], 403);
        }

        $test = Invitation::where('team_id', '=', $request->id)->where('user_email', '=', $user->email)->get();
        if (0 !== count($test)) {
            return response("Une invitation est déjà en cours", 403);
        }

        $invitation = Invitation::create([
            'team_id' => $request->id,
            'user_email' => $user->email,
            'is_from_team' => false
        ]);
        $invitation->team;

        return response($invitation, 201);
    }

    /**
     * Manage an invitation sent to a user by a team
     * @group User management
     * @urlParam id int required The invitation's id
     * @bodyParam status boolean required an email
     */
    public function manageTeamInvitation(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $invitation = Invitation::find($id);

        if (null === $invitation) {
            return response([
                "message" => "Unknown invitation"
            ], 404);
        }

        $team = Team::find($invitation->team_id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $user = $request->user();
        if ($user->email !== $invitation->user_email || true !== $invitation->is_from_team) {
            return response([
                "message" => "Insufficient rights"
            ], 403);
        }

        if (true === $request->status) {
            $team->members()->attach($user->id, ['admin' => false]);
            $FcmToken = [];
            foreach ($team->members as $member) {
                if ($member->device_key !== null) {
                    $FcmToken[] = $member->device_key;
                }
            }
            Larafirebase::withTitle('Teamlist: nouveau membre')
                ->withBody($user->name . ' a rejoint ' . $team->name)
                ->withAdditionalData([
                    'item' => 'TEAM',
                    'id' => $team->id,
                ])
                ->sendMessage($FcmToken);
        }
        $invitation->delete();

        $user->teams;
        $user->invitations;
        return $user;
    }
}
