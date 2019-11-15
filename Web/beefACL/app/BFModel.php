<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Carbonable;
use Carbon\Carbon;

abstract class BFModel extends Model
{

    use Carbonable;

    protected $carbonable = [
        'created_at',
        'updated_at',
    ];

    /**
     * return an array of selected role IDs for multi select filling
     *
     * @return array
     */
    protected function idsFor($plural)
    {
        $ids = array();

        foreach ( $this->$plural as $object ) {
            $ids[$object->id] = $object->id;
        }

        return $ids;
    }

    protected static function listForSelect($includeBlank = false, $nameField = 'name', $idField = 'id')
    {
        $collection = self::all();
        $array = $collection->pluck($nameField, $idField)->toArray();

        if ($includeBlank) {
            $array = array('') + $array;
            // + must be used here to preserve numerical keys!
        }

        return $array;
    }
}
