<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class JsonApiQueryBuilder
{
    public function allowedSorts(): Closure
    {
        return function($allowedSorts) {
            /* @var Builder $this */
            if (request()->filled('sort')) {
                $sortFields = explode(',', request()->input('sort'));

                foreach ($sortFields as $sortField) {
                    $sortDirection = Str::of( $sortField)->startsWith('-')
                        ? 'desc'
                        : 'asc';
                    $sortField = ltrim($sortField, '-');

                    abort_unless(in_array($sortField, $allowedSorts), 400);

                    $this->orderBy($sortField, $sortDirection);
                }
            }

            return $this;
        };
    }

    public function jsonPaginate(): Closure
    {
        return function() {
            /* @var Builder $this */
            return $this->paginate(
                $perPage = request('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends(request()->only('sort', 'filter', 'page.size'));
        };
    }

    public function allowedFilters(): Closure
    {
        return function($allowedFilters) {
            /* @var Builder $this */
            foreach(request('filter', []) as $filter => $value) {
                abort_unless(in_array($filter, $allowedFilters), 400);

                $this->hasNamedScope($filter)
                    ? $this->{$filter}($value)
                    : $this->where($filter, 'LIKE', '%'.$value.'%');

            }

            return $this;
        };
    }

    public function sparseFieldset(): Closure
    {
        return function() {
            /** @var Builder $this */
            if (request()->isNotFilled('fields')) {
                return $this;
            }

            // $resourceType = $this->model->getTable();

            // if (property_exists($this->model, 'resourceType')) {
            //     $resourceType = $this->model->resourceType;
            // }

            // $fields = explode(',', request('fields.articles'));
            $fields = explode(',', request('fields.' . $this->getResourceType()));
            // dd(request('fields'));
            // dd($this->model->getRouteKeyName());
            $routeKeyName = $this->model->getRouteKeyName();
            // if(!in_array('slug', $fields)) {
            if(!in_array($routeKeyName, $fields)) {
                // $fields[] = 'slug';
                $fields[] = $routeKeyName;
            }

            return $this->addSelect($fields);
        };
    }

    public function getResourceType(): Closure
    {
        return function() {
            /** @var Builder $this */
            if (property_exists($this->model, 'resourceType')) {
                return $resourceType = $this->model->resourceType;
            }

            return $this->model->getTable();
        };
    }
}
