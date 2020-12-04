<?php

namespace App\Services;

use App\Models\Requests;
use Carbon\Carbon;
use Illuminate\Support\collection;

abstract class BaseService implements IService
{


    protected $limit = 10;
    protected $column = 'id';
    protected $sort = 'asc';
    protected $mapping = [];

    public function getAll($query)
    {
        return $query->get();
    }

    public function getById($id, $query)
    {
        return $query->find($id);
    }

    public function update($id, $query, $request)
    {
        $data = $query->find($id);

        if ($data) {
            $data->update($request);
        }
        return $data;
    }

    public function delete($id, $query)
    {
        return $query->where('id', $id)->delete();
    }

    public function create($query, $request)
    {
        return $query->create($request);
    }

    public function history($query)
    {
        return $query->get();
    }

    public function getSort($query, $data)
    {
        return $query->orderBy($data['column'], $data['sort']);
    }

    private function checkDefault($request)
    {
        $data = [
            'limit' => $request['limit'] ?? $this->limit,
            'column' => $request['column'] ?? $this->column,
            'sort' => $request['sort'] ?? $this->sort,
        ];
        return $data;
    }

    public function mapping($request, $mapping)
    {
        $this->acceptedfields = $mapping;
        $data = $this->checkDefault($request);
        foreach ($this->acceptedfields->keys() as $key) {
            if ($key == $data['column']) {
                $this->column = $this->acceptedfields->get($key);
                break;
            }
        }
        $data['column'] = $this->column;
        return $data;
    }

    public function createCode($query, $codeFirstCharacter, $newInsert)
    {
        for ($i = 0; $i < 4 - strlen((string)($newInsert->id)); $i++) {
            $codeFirstCharacter = $codeFirstCharacter . "0";
        }
        $codeFirstCharacter = $codeFirstCharacter . $newInsert->id;
        $code = [
            'asset_code' => $codeFirstCharacter
        ];
        return $this->update($newInsert->id, $query, $code);
    }
}
