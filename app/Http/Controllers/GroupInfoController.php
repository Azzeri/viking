<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupInfoController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'short_name' => ['required', 'string', 'min:3', 'max:15'],
            'full_name' => ['required', 'string', 'min:3', 'max:32'],
            'motto' => ['required', 'string', 'min:3', 'max:32'],
            'description' => ['required', 'string', 'min:3', 'max:500'],

            'addr_street' => ['required', 'string', 'min:3', 'max:64'],
            'addr_number' => ['required', 'string', 'min:3', 'max:10'],
            'addr_postCode' => ['required', 'string', 'min:3', 'max:10'],
            'addr_town' => ['required', 'string', 'min:3', 'max:64'],

            'phone' => ['required', 'string', 'min:3', 'max:15'],
            'email' => ['required', 'string', 'min:3', 'max:32'],

            'facebook' => ['nullable', 'string', 'min:3', 'max:128'],
            'youtube' => ['nullable', 'string', 'min:3', 'max:128'],
            'twitter' => ['nullable', 'string', 'min:3', 'max:128'],
            'instagram' => ['nullable', 'string', 'min:3', 'max:128'],
            'tiktok' => ['nullable', 'string', 'min:3', 'max:128'],

            'link1_label' => ['nullable', 'string', 'min:3', 'max:32'],
            'link1_url' => ['nullable', 'string', 'min:3', 'max:128'],
            'link2_label' => ['nullable', 'string', 'min:3', 'max:32'],
            'link2_url' => ['nullable', 'string', 'min:3', 'max:128'],
            'link3_label' => ['nullable', 'string', 'min:3', 'max:25532'],
            'link3_url' => ['nullable', 'string', 'min:3', 'max:128'],
            'link4_label' => ['nullable', 'string', 'min:3', 'max:32'],
            'link4_url' => ['nullable', 'string', 'min:3', 'max:128'],
            'link5_label' => ['nullable', 'string', 'min:3', 'max:32'],
            'link5_url' => ['nullable', 'string', 'min:3', 'max:128'],
            'link6_label' => ['nullable', 'string', 'min:3', 'max:32'],
            'link6_url' => ['nullable', 'string', 'min:3', 'max:128'],
        ]);

        DB::table('group_info')->update($validated);

        return redirect()->back()->with('message', 'Zaktualizowano dane grupy');
    }
}
