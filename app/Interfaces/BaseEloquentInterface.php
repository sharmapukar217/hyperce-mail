<?php

namespace App\Interfaces;

interface BaseEloquentInterface
{
    /**
     * Return all items
     *
     * @param  string  $orderBy
     * @return mixed
     */
    public function all($orderBy = 'id', array $relations = [], array $parameters = []);

    /**
     * Paginate items
     *
     * @param  string  $orderBy
     * @param  int  $paginate
     * @return mixed
     */
    public function paginate($orderBy = 'name', array $relations = [], $paginate = 50, array $parameters = []);

    /**
     * Get all items by a field
     *
     * @return mixed
     */
    public function getBy(array $parameters, array $relations = []);

    /**
     * List all items
     *
     * @param  string  $fieldName
     * @param  string  $fieldId
     * @return mixed
     */
    public function pluck($fieldName = 'name', $fieldId = 'id');

    /**
     * List records limited by a certain field
     *
     * @param  string  $field
     * @param  string|array  $value
     * @param  string  $listFieldName
     * @param  string  $listFieldId
     * @return mixed
     */
    public function pluckBy($field, $value, $listFieldName = 'name', $listFieldId = 'id');

    /**
     * Find a single item
     *
     * @param  int  $id
     * @return mixed
     */
    public function find($id, array $relations = []);

    /**
     * Find a single item by a field
     *
     * @param  string  $field
     * @param  string  $value
     * @return mixed
     */
    public function findBy($field, $value, array $relations = []);

    /**
     * Find a single record by multiple fields
     *
     * @return mixed
     */
    public function findByMany(array $data, array $relations = []);

    /**
     * Find multiple models
     *
     * @return object
     */
    public function getWhereIn(array $ids, array $relations = []);

    /**
     * Store a newly created item
     *
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update an existing item
     *
     * @param  int  $id
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Permanently remove an item from storage
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * Get count of records
     *
     * @param null
     * @return int
     */
    public function count();
}
