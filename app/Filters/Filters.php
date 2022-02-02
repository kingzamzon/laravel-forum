<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $filters = [];
    protected $request, $builder;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters($this->request) as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;    
    }

    public function getFilters($requests)
    {
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);
        return $this->request->only($filters);
    }
}