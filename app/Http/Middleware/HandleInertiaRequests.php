<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
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
                'name' => 'Barbarian',
                'motto' => 'Wykuj z nami swoje marzenia',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',

                'full_name' => 'Kuźnia Barbarian',
                'street' => 'Sezamkowa',
                'building' => '56',
                'appartment' => '3',
                'postal' => '48-330',
                'city' => 'Nysa',

                'phone' => '661 661 661',
                'email' => 'jkowalski@gmail.com'
            ],

            'footerOthers' => [
                ['label' => 'Wilki', 'link' => 'dashboard'],
                ['label' => 'Bobry', 'link' => 'dashboard'],
                ['label' => 'Zające', 'link' => 'dashboard'],
            ],

            'footerSocials' => [
                ['icon' => 'fab fa-instagram-square fa-2x', 'link' => 'dashboard'],
                ['icon' => 'fab fa-facebook-f fa-2x', 'link' => 'dashboard'],
                ['icon' => 'fab fa-twitter fa-2x', 'link' => 'dashboard'],
            ],
        ]);
    }
}
