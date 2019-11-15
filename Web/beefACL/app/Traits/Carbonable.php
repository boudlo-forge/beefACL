<?php

namespace App\Traits;
use Carbon\Carbon;

trait Carbonable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->carbonable)) {
            try {
                $value = new Carbon($value);
            } catch (\Exception $e) {
                // invalid payload, let the $value be
            }
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->carbonable)) {
            $value = new Carbon($value);
        }

        return parent::setAttribute($key, $value);
    }
}
