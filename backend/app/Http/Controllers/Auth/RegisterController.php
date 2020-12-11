<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Domain\Pusher\Models\Profile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $rawPassword;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->create($request->all());
        $user = User::find($user->id);

        event(new Registered($user, $this->rawPassword));

        return response()->json([
            'message' => 'Please confirm email',
            'email' => $user->email
        ], 201);

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'firstName' => 'required|string|min:2|max:50|regex:/^[a-zA-Z\p{Cyrillic}\-]+$/u',
            'lastName' => 'required|string|min:2|max:50|regex:/^[a-zA-Z\p{Cyrillic}\-]+$/u',
            'birthday' => 'date_format:Y-m-d|nullable|before:today|after:1949-12-31',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $this->rawPassword = Str::random(10);

        $data['token'] = $token = bcrypt($this->rawPassword);

        $user = User::create($data);
        $user->password = $token; // password is not filable
        $user->save();

        $user->profile()->create([
            'email' => $data['email'],
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'birthday' => isset($data['birthday']) ? $data['birthday'] : null,
        ]);

        $this->createInMongo($user);
        $this->createInNeo4j($user);

        return $user;
    }

    /**
     * @param $user
     * @throws \Exception
     */
    protected function createInMongo($user) {
        $user = User::with('profile')->find($user->id);
        $user = $user->toArray();
        $profile = $user['profile'];
        $user = array_diff_key($user, array_flip(['profile']));
        $user['created_at'] = new Carbon($user['created_at']);
        $user['updated_at'] = new Carbon($user['updated_at']);
        $profile['created_at'] = new Carbon($profile['created_at']);
        $profile['updated_at'] = new Carbon($profile['updated_at']);
        $user = \Domain\Pusher\Models\User::create($user);
        $user->profile()->save(
            new Profile($profile)
        );
    }

    /**
     * @param $user
     * @throws \Exception
     */
    protected function createInNeo4j($user) {
        $user = User::with('profile')->find($user->id);
        $user = $user->toArray();
        $user['oid'] = $user['id'];
        $user['name'] = $user['profile']['first_name'];
        $user = array_diff_key($user, array_flip(['profile', 'id']));
        $user['created_at'] = new Carbon($user['created_at']);
        $user['updated_at'] = new Carbon($user['updated_at']);
        /** @var User $user */
        \Domain\Neo4j\Models\User::insert($user);
    }


    /**
     * confirm email
     *
     * @param $code
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirm($code)
    {

        $user = User::where('token', $code)->first();

        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);

        } elseif ($user->email_verified_at) {

            return response()->json(['message' => 'already confirmed'], 301);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        $this->guard()->login($user);

        return redirect($this->redirectTo);

    }
}
