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
        request()->validate([
            'direction' => ['in:asc,desc'],
            // 'field' => ['in:id,name, privilege_id']
        ]);

        $query = User::with('privilege');

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('surname', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('nickname', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('email', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('date_birth', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('role', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        }
        else
            $query->orderBy('privilege_id');


        $users = $query->paginate()->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'date_birth' => $user->date_birth,
                'role' => $user->role,
                'privilege_id' => $user->privilege->name,
                'description' => $user->description
            ]);

        return inertia('Admin/Users', [
            'users' => $users,
            'filters' => request()->all(['search', 'field', 'direction']),
        ]);
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
