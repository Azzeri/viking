<?php

namespace App\Http\Traits;

use App\Models\Privilege;
use App\Models\Post;

trait PostTrait
{
  /**
   * @before
   */
  public function setupPost($priv)
  {
    return $priv;
  }
}
