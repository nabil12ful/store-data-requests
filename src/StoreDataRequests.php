<?php
namespace Nabil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StoreDataRequests
{
    /**
     * @var array
     */
    protected static $attrs = [];

    /**
     * @var Illuminate\Http\Request
     */
    protected static $request;

    /**
     * @var Model
     */
    protected static $model;

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
    public static function model($model)
    {
        if(is_string($model))
        {
            $ex = explode('\\', $model);
            if(count($ex) > 1)
            {
                Self::$model = $model;
            }else{
                Self::$model = 'App\Models\\'.$model;
            }
        }elseif(get_class($model))
        {
            Self::$model = new $model;
        }
        return new Self;
    }

    /**
     * get Request data fields & Attrributes of Model you want to save data 
     * But Field name = Column Name
     * @param Illuminate\Http\Request $request
     * @param Array $attributes
     * @param Model $model Can be NULL & use model() METHOD
     * @return Self
     */
    public static function make(Request $request, array $attributes, $model = null)
    {
        if(!is_null($model))
        {
            Self::model($model);
        }
        Self::$attrs = $attributes;
        Self::$request = $request;
        return new Self;
    }

    /**
     * Store Data to Model
     */
    public static function store()
    {
        $data = [];
        foreach(Self::$attrs as $attr)
        {
            $data[$attr] = Self::$request->{$attr};
        }
        return Self::$model::create($data);
    }

    /**
     * Update data on Model
     * 
     * @param Int $id
     */
    public static function update($id)
    {
        $model = Self::$model::findOrFail($id);
        foreach(Self::$attrs as $attr)
        {
            $model->{$attr} = Self::$request->{$attr};
        }
        $model->update();
    }

    /**
     * Store data to model & upload file
     * 
     * @param String $path
     */
    public static function storeHasFile($path)
    {
        $data = [];
        foreach(Self::$attrs as $attr)
        {
            if(Self::$request->hasfile($attr))
            {
                $data[$attr] = Self::uploadFile(Self::$request->$attr, $path);
            }else{
                $data[$attr] = Self::$request->{$attr};
            }
        }
        return Self::$model::create($data);
    }

    /**
     * Update data in Model & Upload File
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFile($id, $path)
    {
        $model = Self::$model::findOrFail($id);
        foreach(Self::$attrs as $attr)
        {
            if(!empty(Self::$request->$attr) && Self::$request->hasfile($attr))
            {
                File::delete(Self::parsePath($path).$model->{$attr});
                $model->{$attr} = Self::uploadFile(Self::$request->{$attr}, $path);
            }
            elseif(empty(Self::$request->$attr))
            {
                $model->{$attr} = $model->{$attr};
            }
            else{
                $model->{$attr} = Self::$request->{$attr};
            }
        }
        $model->update();
    }

    /**
     * Upload file
     * @param RequestHasFile $file
     * @param String $path
     */
    public static function uploadFile($file, $path)
    {
        $filename = time() . $file->getClientOriginalname();
        $file->move($path, $filename);
        return $filename;
    }

    /**
     * Delete record in Model
     * 
     * @param Int $id
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function delete(Int $id, $model = null)
    {
        $model == null ? $model = Self::$model::findOrFail($id) : Self::model($model) && $model = Self::$model::findOrFail($id);
        $model->delete();
    }

    /**
     * Delete record in DB & Delete File uploaded
     * 
     * @param Int $id
     * @param String $path
     * @param String|Array $columns By default = 'image'
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function deleteHasFiles(Int $id, String $path, string|array $columns = 'image', $model = null)
    {
        $model == null ? $model = Self::$model::findOrFail($id) : Self::model($model) && $model = Self::$model::findOrFail($id);
        if(is_array($columns))
        {
            foreach($columns as $column)
            {
                File::delete(Self::parsePath($path).$model->$column);
            }
        }
        if(is_string($columns))
        {
            File::delete(Self::parsePath($path).$model->$columns);
        }
        $model->delete();
    }

    /**
     * Parse Path 
     * 
     * @param String $path
     */
    protected static function parsePath($path)
    {
        $folders = explode('/', $path);
        $path = '';
        foreach($folders as $folder)
        {
            !empty($folder) ? $path .= $folder .'/' : $path .= $folder;
        }
        return $path;
    }
}