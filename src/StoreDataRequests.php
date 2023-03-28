<?php

/**
 * Develop by Nabil Hamada
 */

namespace Nabil;

use Illuminate\Http\Request;
use Nabil\Contracts\StoreDataRequestsInterface as StoreData;

class StoreDataRequests implements StoreData
{
    use Files, Validate, Encrypt;

    /**
     * @var array
     */
    protected static $attrs = [];

    /**
     * @var array
     */
    protected static $media = [];

    /**
     * @var array
     */
    protected static $encryptColumns = [];

    /**
     * @var Illuminate\Http\Request
     */
    protected static $request;

    /**
     * @var Model
     */
    protected static $model;

    /**
     * @var string
     */
    protected static $path = 'upload/';

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
     * @param Array $mediaColumns Attributes has files you want to upload it
     * @param Model $model Can be NULL & use model() METHOD
     * @return Self
     */
    public static function make(Request $request, array $attributes, array $mediaColumns = [], $model = null)
    {
        if(!is_null($model))
        {
            Self::model($model);
        }
        Self::$attrs = array_merge($attributes, $mediaColumns);
        Self::$media = $mediaColumns;
        Self::$request = $request;
        return new Self;
    }

    /**
     * Encrypt Columns data
     * 
     * @param Array $columns
     */
    public static function encrypt(Array $columns)
    {
        Self::$encryptColumns = $columns;
        return new self;
    }

    /**
     * Store Data to Model
     * 
     * @param String $path by default = 'upload/'
     */
    public static function store(String $path = null)
    {
        $data = [];
        $path == null ? Self::$path : $path;
        foreach(Self::$attrs as $attr)
        {
            if(Self::$request->hasfile($attr) && in_array($attr, Self::$media))
            {
                $data[$attr] = Self::uploadFile(Self::$request->$attr, $path);
            }elseif(in_array($attr, Self::$encryptColumns)){
                $data[$attr] = Self::encrypt_data(Self::$request->{$attr});
            }else{
                $data[$attr] = Self::$request->{$attr};
            }
        }
        return Self::$model::create($data);
    }

    /**
     * Update data on Model
     *
     * @param Int $id
     * @param String $path by default = 'upload/'
     */
    public static function update($id, String $path = null)
    {
        $model = Self::$model::findOrFail($id);
        $path == null ? Self::$path : $path;
        foreach(Self::$attrs as $attr)
        {
            if(!empty(Self::$request->$attr) && Self::$request->hasfile($attr))
            {
                Self::deleteFile($path, $model->{$attr});
                $model->{$attr} = Self::uploadFile(Self::$request->{$attr}, $path);
            }
            elseif(empty(Self::$request->$attr) && in_array(Self::$request->$attr, Self::$media))
            {
                $model->{$attr} = $model->{$attr};
            }elseif(in_array($attr, Self::$encryptColumns)){
                $model->{$attr} = Self::encrypt_data(Self::$request->{$attr});
            }
            else{
                $model->{$attr} = Self::$request->{$attr};
            }
        }
        $model->update();
    }

    /**
     * Update data on Model with Validation
     *
     * @param Int $id
     * @param String $path by default = 'upload/'
     */
    public static function updateValidated($id, String $path = null)
    {
        $model = Self::$model::findOrFail($id);
        $path == null ? Self::$path : $path;
        $attrs = array_keys(Self::$attrs);
        $media = array_keys(Self::$media);
        if(Self::validateOnUpdate($id)->fails())
        {
            return Self::validateOnUpdate($id);
        }else{
            foreach($attrs as $attr)
            {
                if(!empty(Self::$request->$attr) && Self::$request->hasfile($attr) && in_array($attr, $media))
                {
                    Self::deleteFile($path, $model->{$attr});
                    $model->{$attr} = Self::uploadFile(Self::$request->{$attr}, $path);
                }
                elseif(empty(Self::$request->$attr) && in_array($attr, $media))
                {
                    $model->{$attr} = $model->{$attr};
                }
                elseif(in_array($attr, Self::$encryptColumns))
                {
                    $model->{$attr} = Self::encrypt_data(Self::$request->{$attr});
                }
                else{
                    $model->{$attr} = Self::$request->{$attr};
                }
            }
            $model->update();
            return false;
        }
    }

    /**
     * Store data to model & upload files with validate
     *
     * @param String $path by default = 'upload/'
     */
    public static function storeValidated(String $path = null)
    {
        $attrs = array_keys(Self::$attrs);
        $path == null ? Self::$path : $path;
        if(Self::validate()->fails())
        {
            return Self::validate();
        }else{
            $data = [];
            foreach($attrs as $attr)
            {
                if(Self::$request->hasfile($attr))
                {
                    $data[$attr] = Self::uploadFile(Self::$request->$attr, $path);
                }
                elseif(in_array($attr, Self::$encryptColumns))
                {
                    $data[$attr] = Self::encrypt_data(Self::$request->{$attr});
                }else{
                    $data[$attr] = Self::$request->{$attr};
                }
            }
            return Self::$model::create($data);
        }
    }

    /**
     * Delete record in Model
     *
     * @param Int $id
     * @param String $path by default = 'upload/'
     * @param Model $model Can be NULL & use model() METHOD
     */
    public static function delete(Int $id, String $path = null, $model = null)
    {
        $model == null ? $model = Self::$model::findOrFail($id) : Self::model($model) && $model = Self::$model::findOrFail($id);
        $path == null ? Self::$path : $path;
        foreach(Self::$media as $column)
        {
            Self::deleteFile($path, $model->$column);
        }

        $model->delete();
    }
}
