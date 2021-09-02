<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 8/1/18
 * Time: 10:01 AM
 */

namespace App\Core;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ImageService //extends TatucoService
{

    /**
     * @param $images
     * @param string $id
     * @return bool|string
     */
    public function image($images, $id = 'zippyttech')
    {
        try{
            $route = rtrim(app()->basePath('public/'), '/') . "/images/";
            $route_web = env('CUSTOM_URL') . '/images/';
            $now = Carbon::now()->format('Y-m-d');
            $upload_dir =$route;
            $img = $images;
            $ext = $this->get_extension($img);
            $img = str_replace('data:image/'.$ext.';base64,', '', $img);
            $data = base64_decode($img);
            $var_for = uniqid().'-'.$id.'-'.$now. '.'.$ext;
            $file = $upload_dir . $var_for;
            $image = $route_web . $var_for;
            $success = file_put_contents($file, $data);
            return $success ?$image: false;
        }catch (\Exception $e){

            Log::critical($e->getMessage());
            return null;
        }

    }

    /**
     * @named Function valid Images
     * @param $values($images)
     * @return false|string
     */
    public function images($values){
        if(!is_array($values)){
            return null;
        }
        $i = 1;
        $image_array = [];
        foreach ($values as $value){
            $image_array[] = filter_var($value, FILTER_VALIDATE_URL) ? $value : ["image".$i => $this->image($value)];
            $i++;
        }
        return json_encode($image_array);
    }

    /**
     * @param $string
     * @return int
     * @throws Exception
     */
    public function get_extension($string)
    {
        $extension="";
        if(!empty($string)){
            $formats = ["jpg", "jpeg", "png", "gif",'icoñ..'];
            if(substr($string,0,4)=='http')
            {
                return $extension=3;
            }else {
                $data = $string;
                $pos = strpos($data, ';');
                $type = explode(':', substr($data, 0, $pos))[1];
                $extension = preg_split("[/]", $type);
                return $extension[1];
            }
        }else{
            return null;
        }
    }
}