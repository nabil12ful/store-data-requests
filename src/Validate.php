<?php
namespace Nabil;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

trait Validate
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
     *  Validate data
     */
    protected static function validate()
    {
        return Validator::make(Self::$request->all(), Self::$attrs);
    }

    /**
     *  Validate data on update
     */
    protected static function validateOnUpdate($id)
    {
        $attrs = Self::$attrs;
        foreach($attrs as &$attr)
        {
            foreach($arr = explode('|',$attr) as &$rule)
            {
                if(explode(':', $rule)[0] == 'unique')
                {
                    $attr .= ','.$id;
                }
                elseif(in_array('file', $arr) || in_array('image', $arr))
                {
                    $attr = Str::replaceFirst('required', 'nullable', $attr);
                }
            }
        }
        return Validator::make(Self::$request->all(), $attrs);
    }
}