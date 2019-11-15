<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessCategory extends BFModel
{
    public function nodes() {
        return $this->hasmany('App\Node');
    }
}
