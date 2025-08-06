<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Str;
/**
 * Class S3uploaderServices
 * @package App\Services
 */
class S3uploaderServices extends BaseController
{
    public function uploads3Storage($file, $path,$pathCurrentFile = null)
    {
        // refrence https://www.codecheef.org/article/laravel-8-upload-image-to-storage-folder-example
        if($file) {
            
            if($pathCurrentFile) {

                $this->removeFile($pathCurrentFile);  
            }
            
            $file_name        = $file->getClientOriginalName();
            $file_type        = $file->getClientOriginalExtension();
            $file_name_store  =  rand(2,1000).'_'.time().'_'.Str::replace(' ','_',$file->getClientOriginalName());
            $filePath         = ''.$path.'/'.$file_name_store;

            Storage::disk('s3')->put($filePath, File::get($file));

            $file = [
                'fileNameOriginal'      => $file_name,
                'fileNameStore'         => $file_name_store,
                'fileType'              => $file_type,
                'filePath'              => $filePath,
                'fileSize'              => $this->fileSize($file)
            ];

            return $this->handleArrayResponse($file,'upload image success','info');
        }
    }
    public function removeFile($pathCurrentFile) {

       return Storage::disk('s3')->delete($pathCurrentFile);   
        
    }
    public function fileSize($file, $precision = 2)
    {   
        $size = $file->getSize();

        if ( $size > 0 ) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
