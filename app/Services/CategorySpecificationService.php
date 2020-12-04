<?php


namespace App\Services;


use App\Models\CategorySpecification;

class CategorySpecificationService extends BaseService
{
    public function getRequireSpecification(){
        $query = (new \App\Models\CategorySpecification)->OfCategorySpecification();
        return $this->getAll($query);
    }
}
