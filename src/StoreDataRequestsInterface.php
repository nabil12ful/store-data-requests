<?php
namespace Nabil;

use Illuminate\Http\Request;

interface StoreDataRequests 
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
    public function model($model);

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
     * Update data on Model
     * 
     * @param Int $id
     */
    public static function update($id);

    /**
     * Store data to model & upload file
     * 
     * @param String $path
     */
    public static function storeHasFile($path);

    /**
     * Update data in Model & Upload File
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFile($id, $path);

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
