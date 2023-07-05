<?php

namespace App\Http\Traits;

use App\Models\Privilege;
use App\Models\User;

trait UserTrait
{
  /** @var User $user **/
  protected $user;
  protected $priv;


  /**
   * @before
   */
  public function setupUser($priv)
  {
    

    // $this->afterApplicationCreated(function () {
    //   $this->user = 
    //     User::factory()->create([
    //       'privilege_id' => $this->priv
    //     ]);
    // });

      // $this->seed();
      // if (!$privilege) {
      // //   return;
      // // }
      // $this->user = User::factory()->create();
      // $this->user->update([
      //   'privilege_id' => $privilege,
      // ]);
      // $this->post('/login', [
      //   'email' => $this->user->email,
      //   'password' => 'qwerty',
      // ]);

  }

  // public function authenticated()
  // {
  //   $this->post('/login', [
  //     'email' => $this->user->email,
  //     'password' => 'qwerty',
  //   ]);
  // }

  // if (!$privilege) {
  //   return;
  // }

  // $user = User::factory()->create();
  // $user->update([
  //   'privilege_id' => $privilege,
  // ]);

  // $this->post('/login', [
  //   'email' => $user->email,
  //   'password' => 'qwerty',
  // ]);
}
