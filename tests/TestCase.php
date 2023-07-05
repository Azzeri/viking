<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $seed = true;
    protected $user;

    protected const STATUS_SUCCESS = 200;
    protected const STATUS_REDIRECTED = 302;
    protected const STATUS_PERMISSION_DENIED = 403;

    protected const URL_INDEX = '/';

    /**
     * Create and authenticate user with specified privilege
     * @author mwaloszczyk
     */
    protected function arrangeUser(?int $privilege)
    {
        if ($privilege) {
            $this->user = User::factory()->create([
                'privilege_id' => $privilege
            ]);

            $this->actingAs($this->user);
        }
    }
}
