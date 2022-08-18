<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Team management
 *
 * APIs for managing teams
 */
class TeamController extends Controller
{
    private function populateTeam($team) {
        $team->members;
        $team->invitations;
        foreach ($team->invitations as $invitation) {
            $invitation->user;
        }
    }

    /**
     * Create a team
     * and set current user as member and admin
     * @bodyParam name string required The team's name
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $team = Team::create([
            'name' => $request->name
        ]);
        $user = $request->user();

        $team->members()->attach($user->id, ['admin' => true]);
        $this->populateTeam($team);

        return $team;
    }

    /**
     * Get a team by id
     * @urlParam id int required The team's id
     */
    public function show($id)
    {
        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Groupe inconnu"
            ], 404);
        }
        $this->populateTeam($team);

        return response($team, 200);
    }

    /**
     * Get teams by name
     * @bodyParam name string required The team's name
     */
    public function getTeamsByName(Request $request)
    {

        return Team::where('name', 'like', '%' . $request->query('name') . '%')->get();
    }

    /**
     * Update a team
     * @urlParam id int required  The team's id
     * @bodyParam name string required The team's name
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required',
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
        $team->name = $request->name;
        $team->save();

        $this->populateTeam($team);

        return response($team, 200);
    }

    /**
     * Manage the admin status of a team user
     * @urlParam id int required The team's id
     * @bodyParam id int required The user's id to manage
     * @bodyParam admin boolean required The new user's status
     */
    public function manageAdmin(Request $request, $id)
    {
        try {
            $request->validate([
                'id' => 'required',
                'admin' => 'required',
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

        $adminCount = 0;
        foreach ($team->members as $elem) {
            if (true === $elem->pivot->admin) {
                $adminCount++;
            }
        }
        if (2 > $adminCount && false === $request->admin) {
            return response([
                "message" => "Team must have at least one admin"
            ], 403);
        }

        $team->members()->updateExistingPivot($request->id, [
            'admin' => $request->admin,
        ]);

        $team = Team::find($id);
        $this->populateTeam($team);

        return response($team, 200);
    }

    /**
     * Remove a member from team
     * @urlParam id int required The team's id
     * @bodyParam id int required The user's id to remove
     */
    public function removeMember(Request $request, $id)
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

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $user = $request->user();
        if ($user->id === $request->id) {
            return response([
                "message" => "You can't delete yourself. Use 'leave' instead"
            ], 403);
        }

        $team->members()->detach($request->id);

        $this->populateTeam($team);

        return response($team, 200);
    }

    /**
     * Delete a team
     * @urlParam id int required The team's id
     */
    public function delete($id)
    {
        $group = Team::find($id);
        if (null === $group) {
            return response([
                "message" => "Unknown team"
            ], 404);
        }

        $group->delete();
        return response([
            'message' => 'Team deleted'
        ], 200);
    }
}
