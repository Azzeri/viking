<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'privileges' => [
                'IS_ADMIN' => 1,
                'IS_COORDINATOR' => 2
            ],

            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],

            'navigation' => [
                ['label' => 'Aktualności', 'link' => 'news.index', 'icon' => 'fas fa-newspaper'],
                ['label' => 'Galeria', 'link' => 'gallery.index', 'icon' => 'far fa-images'],
                ['label' => 'Sklep', 'link' => 'store.index', 'icon' => 'fas fa-shopping-basket'],
                ['label' => 'Wydarzenia', 'link' => 'events.index', 'icon' => 'far fa-calendar-alt'],
                ['label' => 'O nas', 'link' => 'about.index', 'icon' => 'far fa-address-card']
            ],

            'groupInfo' => [
                'name' => DB::table('group_info')->first()->short_name,
                'motto' => DB::table('group_info')->first()->motto,
                'description' => DB::table('group_info')->first()->description,

                'full_name' => DB::table('group_info')->first()->full_name,
                'addr_street' => DB::table('group_info')->first()->addr_street,
                'addr_number' => DB::table('group_info')->first()->addr_number,
                'addr_postCode' => DB::table('group_info')->first()->addr_postCode,
                'addr_town' => DB::table('group_info')->first()->addr_town,

                'phone' => DB::table('group_info')->first()->phone,
                'email' => DB::table('group_info')->first()->email,
            ],

            'footerOthers' => [
                ['label' => DB::table('group_info')->first()->link1_label, 'link' => DB::table('group_info')->first()->link1_url],
                ['label' => DB::table('group_info')->first()->link2_label, 'link' => DB::table('group_info')->first()->link2_url],
                ['label' => DB::table('group_info')->first()->link3_label, 'link' => DB::table('group_info')->first()->link3_url],
                ['label' => DB::table('group_info')->first()->link4_label, 'link' => DB::table('group_info')->first()->link4_url],
                ['label' => DB::table('group_info')->first()->link5_label, 'link' => DB::table('group_info')->first()->link5_url],
                ['label' => DB::table('group_info')->first()->link6_label, 'link' => DB::table('group_info')->first()->link6_url],
            ],

            'footerSocials' => [
                ['icon' => 'fab fa-instagram-square fa-2x', 'link' => DB::table('group_info')->first()->instagram],
                ['icon' => 'fab fa-facebook-f fa-2x', 'link' => DB::table('group_info')->first()->facebook],
                ['icon' => 'fab fa-twitter fa-2x', 'link' => DB::table('group_info')->first()->twitter],
                ['icon' => 'fab fa-tiktok fa-2x', 'link' => DB::table('group_info')->first()->tiktok],
                ['icon' => 'fab fa-youtube fa-2x', 'link' => DB::table('group_info')->first()->youtube],

            ],
        ]);
    }
}
