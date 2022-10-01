<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    /**
     * Create a user
     * @bodyParam name string required The user's nickname
     * @bodyParam email string required The user's email. Must be unique.
     * @bodyParam password string required The user's password
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 400);
        }

        $testExist = User::where('email', $request->email)->first();
        if ($testExist) {
            return response([
                'message' => ['Already used email']
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('event_manager')->plainTextToken;

        $user->teams;
        $user->invitations;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Login a user and get a token
     * @bodyParam email string required The user's email.
     * @bodyParam password string required The user's password
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Ces identifiant ne correspondent pas']
            ], 404);
        }

        $token = $user->createToken('event_manager')->plainTextToken;

        $user->teams;
        $user->invitations;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function storeDeviceKey(Request $request)
    {
        $user = $request->user();
        $user->update(['device_key' => $request->deviceKey ?: null]);
        return response()->json(['Token successfully stored.']);
    }

    /**
     * Get a user by its token and refresh token
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->teams;
        $user->invitations;

        $token = $user->createToken('event_manager')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    /**
     * Get users by name or email
     * @urlParam name string required The user's name or email
     */
    public function getUserByNameOrEmail(Request $request, $name)
    {
        return User::where('name', 'like', '%' . $request->name . '%')->orWhere('email', 'like', '%' . $name . '%')->take(10)->get();
    }

    /**
     * Leave a team
     * @urlParam id int required  The team's id
     */
    public function leaveTeam(Request $request, $id)
    {
        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === intval($request->route('id')) && true === $team->pivot->admin) {
                return response([
                    "message" => "Can't leave a team when you're admin"
                ], 403);
            }
        }

        $team = Team::find($id);
        if (null === $team) {
            return response([
                "message" => "Unkwown team"
            ], 404);
        }

        $team->members()->detach($user->id);

        $response = [
            'message' => 'team leaved',
        ];
        return response($response, 201);
    }

    public function askResetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => ['Invalid or missing fields']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response([
                'message' => ['Email inconnu']
            ], 404);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        //Get the token just created above
        $tokenData = (array)DB::table('password_resets')
            ->where('email', $request->email)->first();

        $link = config('base_url') . 'password/reset/' . $tokenData['token'] . '?email=' . urlencode($user->email);

        $mailData = [
            "name" => $user->name,
            "link" => $link
        ];

        Mail::to($user->email)->send(new ResetPasswordMail($mailData));
        return response($link, 200);
    }

    public function resetPassword(Request $request)
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'token' => 'required' ]);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Please complete the form']);
        }

        $password = $request->password;

        // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();

        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return view('auth.passwords.email');

        $user = User::where('email', $tokenData->email)->first();

        // Redirect the user back if the email is invalid
        if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);

        //Hash and update the new password
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        //Send Email Reset Success Email
//        if ($this->sendSuccessEmail($tokenData->email)) {
//            return view('index');
//        } else {
//            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
//        }
    }
}
