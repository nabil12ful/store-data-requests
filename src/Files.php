<?php
namespace Nabil;

use Illuminate\Support\Facades\File;

trait Files
{
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

    public static function deleteFile($path, $file)
    {
        File::delete(Self::parsePath($path).'/'.$file);
    }
}
