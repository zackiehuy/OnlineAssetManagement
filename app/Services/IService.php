<?php

namespace App\Services;

interface IService
{
    public function getAll($query);
    public function getById($id, $query);
    public function update($id, $query, $request);
    public function delete($id, $query);
    public function create($query, $request);
}
