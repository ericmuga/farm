<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class SearchService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function search($request)
    {
        $query = $this->model->newQuery();

        if ($request->has('search')) {
            $searchValue = $request->input('search');
            
            $table = $this->model->getTable();
            $columns = $this->getStringColumns($table);
            
            $query->where(function ($query) use ($columns, $searchValue) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $searchValue . '%');
                }
            });
        }

        return $query ->paginate(15)
                    ->withQueryString()
                    ->appends($request->all());
    }

    protected function getStringColumns($table)
    {
        $columns = [];

        $schema = $this->model->getConnection()->getDoctrineSchemaManager();
        $tableColumns = $schema->listTableColumns($table);

        foreach ($tableColumns as $column) {
            if ($column->getType()->getName() === 'string') {
                $columns[] = $column->getName();
            }
        }

        return $columns;
    }
}
