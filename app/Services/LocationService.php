<?php

namespace App\Services;

use App\Models\Location;

class LocationService extends BaseService
{
    public function getAllLocation()
    {
        $query = Location::query();
        return $this->getAll($query);
    }
    public function getExtendById($id)
    {
        $query = Location::extendById($id);
        return $this->getById($id, $query);
    }
}
