<?php
namespace Nabil\Contract;

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
    public static function make(Request $request, array $attributes, $model = null);

    /**
     * Store Data to Model
     */
    public static function store();

    /**
     * Store Data to Model with validation
     */
    public static function storeWithValidate();

    /**
     * Update data on Model
     * 
     * @param Int $id
     */
    public static function update($id);

    /**
     * Update data on Model with Validation
     * 
     * @param Int $id
     */
    public static function updateWithValidate($id);

    /**
     * Store data to model & upload files
     * 
     * @param String $path
     */
    public static function storeHasFiles($path);

    /**
     * Store data to model & upload files with validate
     * 
     * @param String $path
     */
    public static function storeHasFilesValidate($path);

    /**
     * Update data in Model & Upload Files
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFiles($id, $path);

    /**
     * Update data in Model & Upload Files with validate
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFilesValidate($id, $path);

    /**
     * Delete record in Model
     * 
     * @param Int $id
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function delete(Int $id, $model = null);

    /**
     * Delete record in DB & Delete File uploaded
     * 
     * @param Int $id
     * @param String $path
     * @param String|Array $columns By default = 'image'
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function deleteHasFiles(Int $id, String $path, string|array $columns = 'image', $model = null);
}
