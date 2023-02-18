<?php
namespace Nabil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Nabil\Contract\StoreDataRequestsInterface as StoreData;

class StoreDataRequests implements StoreData
{
    use Files;

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
     * Store Data to Model with validation
     */
    public static function storeWithValidate()
    {
        $attrs = array_keys(Self::$attrs);
        $valid = Validator::make(Self::$request->all(), Self::$attrs);
        if($valid->fails())
        {
            return $valid;
        }else{
            $data = [];
            foreach($attrs as $attr)
            {
                $data[$attr] = Self::$request->{$attr};
            }
            return Self::$model::create($data);
        }
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
     * Update data on Model with Validation
     * 
     * @param Int $id
     */
    public static function updateWithValidate($id)
    {
        $attrs = array_keys(Self::$attrs);
        $valid = Validator::make(Self::$request, Self::$attrs);
        if($valid->fails())
        {
            return $valid;
        }else{
            $model = Self::$model::findOrFail($id);
            foreach($attrs as $attr)
            {
                $model->{$attr} = Self::$request->{$attr};
            }
            $model->update();
        }
    }

    /**
     * Store data to model & upload files
     * 
     * @param String $path
     */
    public static function storeHasFiles($path)
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
     * Store data to model & upload files with validate
     * 
     * @param String $path
     */
    public static function storeHasFilesValidate($path)
    {
        $attrs = array_keys(Self::$attrs);
        $valid = Validator::make(Self::$request, Self::$attrs);
        if($valid->fails())
        {
            return back()->withInput()->withErrors($valid);
        }else{
            $data = [];
            foreach($attrs as $attr)
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
    }

    /**
     * Update data in Model & Upload Files
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFiles($id, $path)
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
     * Update data in Model & Upload Files with validate
     * 
     * @param Int $id
     * @param String $path 
     */
    public static function updateHasFilesValidate($id, $path)
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
}