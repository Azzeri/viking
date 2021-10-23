<?php

namespace App\Http\Controllers;

use App\Models\Privilege;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return inertia('Admin/Users', ['users' => $users]);
    }

    public function generateRegistrationLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'min:3', 'max:128']
        ]);

        DB::table('allowed_mails')->insert(['email' => $request->email]);

        $details = [
            'title' => 'Link do rejestracji',
            'body' => 'Przesyłamy Twój link do rejestracji',
            'link' => URL::signedRoute('registerMember', ['email' => $request->email, 'role' => $request->role])
        ];

        Mail::to($request->email)->send(new \App\Mail\sendRegistrationLink($details));

        return redirect()->back()->with('Message', 'Pomyślnie wygenerowano link');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMember(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'surname' => ['required', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'nickname' => ['nullable', 'string', 'min:3', 'max:32', 'alpha_dash'],
            'role' => ['required', 'string', 'min:3', 'max:128'],
            'date_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users', 'exists:allowed_mails'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request['name'],
            'surname' => $request['surname'],
            'nickname' => $request['nickname'],
            'date_birth' => $request['date_birth'],
            'privilege_id' => Privilege::IS_GROUP_MEMBER,
            'role' => $request['role'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        DB::table('allowed_mails')->where('email', $request->email)->delete();

        return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
