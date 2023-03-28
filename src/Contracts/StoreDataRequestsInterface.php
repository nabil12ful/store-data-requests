<?php
/**
 * Develop by Nabil Hamada 2023
 */
namespace Nabil\Contracts;

use Illuminate\Http\Request;

interface StoreDataRequestsInterface
{
    /**
     *  set Model
     *
     * @param Model $model
     * You can write like ('Model')
     * OR ('\App\Models\Model')
     * OR (Model::class)
     * OR (\App\Models\Model::class)
     * OR (\App\Models\Model)
     */
    public static function model($model);

    /**
     * get Request data fields & Attrributes of Model you want to save data
     * But Field name = Column Name
     * @param Illuminate\Http\Request $request
     * @param Array $attributes
     * @param Model $model Can be NULL & use model() METHOD
     * @return Self
     */
    public static function make(Request $request, array $attributes, array $mediaColumns = [], $model = null);

    /**
     * Encrypt Data
     * 
     * @param Array $columns
     */
    public static function encrypt(Array $columns);

    /**
     * Store Data to Model
     * 
     * @param String $path by default = 'upload/'
     */
    public static function store(String $path = null);

    /**
     * Store Data to Model with validation
     * 
     * @param String $path by default = 'upload/'
     */
    public static function storeValidated(String $path = null);

    /**
     * Update data on Model
     *
     * @param Int $id
     * 
     * @param String $path by default = 'upload/'
     */
    public static function update($id, String $path = null);

    /**
     * Update data on Model with Validation
     *
     * @param Int $id
     * 
     * @param String $path by default = 'upload/'
     */
    public static function updateValidated($id, String $path = null);

    /**
     * Delete record in Model
     *
     * @param Int $id
     * @param String $path by default = 'upload/'
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function delete(Int $id, String $path = null, $model = null);
}
