<?php


class cImage {

    const logo = 'logo.png';

    static public function isImageMagic() {
        return isImageMagick;
    }

    static public function command($command) {
        if(cImageMagickPath) {
            return cImageMagickProg . cImageMagickPath . $command . cImageMagickProg;
        } else {
            return $command;
        }
    }

    static public function createLogo($size, $notice) {
        if(!self::isImageMagic()) return;
        $notice = addslashes($notice);
        $path = cWWWPath . path_watermark;
        $logo = $path . cImage::logo;
        exec($com = self::command('convert') ." -size 400x100 xc:grey30 -font Arial -pointsize $size -gravity center  -draw \"fill grey70  text 0,0  '{$notice}'\" {$path}stamp_fgnd.png");
        exec($com = self::command('convert') ." -size 400x100 xc:black -font Arial -pointsize $size -gravity center  -draw \"fill white  text  1,1  '{$notice}' text  0,0  '{$notice}' fill black  text -1,-1 '{$notice}'\" +matte {$path}stamp_mask.png");
        exec($com = self::command('composite') ." -compose CopyOpacity {$path}stamp_mask.png {$path}stamp_fgnd.png {$logo}");
        exec($com = self::command('mogrify') ." -trim +repage {$logo}");
    }

    static public function watermark($image) {
       if(!self::isImageMagic()) return;
        $place = cSettings::read('watermark', 'place');
        $type = cSettings::read('watermark', 'type');
        if($type==='text') {
            $logo = cWWWPath . path_watermark . cImage::logo;
        } else {
            $logo = cWWWPath . path_watermark . cSettings::read('watermark', 'image');
        }
        list($wLogom, $hLogo) = getimagesize($logo);
        list($wImage, $hImage) = getimagesize($image);
        if($wLogom>($hLogo*.7)) {
            $wLogo = ceil($wImage*.7);
            $hLogo = ceil($hImage*.7);
            $tmp = tempnam(cWWWPath . path_watermark, 'watermark');
            copy($logo, $tmp);
            self::resize($tmp, $wLogo, $hLogo);
            $logo = $tmp;
        }
        exec(self::command('composite') ." -gravity {$place} -geometry +10+10 {$logo} {$image} {$image}");
    }

    static public function resize($image, $width, $height) {
        if (!file_exists($image)) return false;
        $size = getimagesize($image);
        if ($size === false) return false;

        // Определяем исходный формат по MIME-информации, предоставленной
        // функцией getimagesize, и выбираем соответствующую формату
        // imagecreatefrom-функцию.
        $img_x = $size[0];
        $img_y = $size[1];
        if(is_array($width) or is_array($height)) {
            $width = get($width, 'max');
            $height = get($height, 'max');

            if($img_x<$width and $img_y<$height) return;

        }

        if($width===null or $width>$img_x) $width=$img_x;
        if($height===null)
        {    $height = $img_y/($img_x/$width);
        }
        if($height===null or $height>$img_y) $height = $img_y;
        if($width===$img_x and $height===$img_y) return true;

        if(self::isImageMagic()) {
            exec($com = self::command('mogrify') ." -resize {$width}x{$height} $image");
            //pre($com);
            return;
        } else {

            $quality=100;   // качество сжатия
            $rgb=0xFFFFFF; // цвет фона

            $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	        $icfunc = "imagecreatefrom" . $format;
	        if (!function_exists($icfunc)) return false;

        	$x_ratio = $width / $img_x;
        	$y_ratio = $height / $img_y;

        	$ratio		 = min($x_ratio, $y_ratio);
        	// координаты разные
        	$use_x_ratio = ($x_ratio == $ratio);

        	$new_width	= $use_x_ratio  ? $width  : floor($img_x * $ratio);
        	$new_height	= !$use_x_ratio ? $height : floor($img_y * $ratio);
        	$new_left	= $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
        	$new_top	= !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);


        	$image_in = $icfunc($image);
        	$image_out = imagecreatetruecolor($new_width, $new_height);

        	imagefill($image_out, 0, 0, $rgb);
        	imagecopyresampled($image_out, $image_in, 0, 0, 0, 0,
        	   $new_width, $new_height, $img_x, $img_y);
        	imagedestroy($image_in);

        	$icfunc = "image" . $format;
        	$icfunc($image_out, $image, $quality);
        	imagedestroy($image_out);
        	return true;
        }
    }

    static public function thumbnail($small, $oWidth, $oHeight, $x1, $y1, $w, $h, $width, $height) {
        exec($com = self::command('convert') ." -size {$oWidth}x{$oHeight} {$small} -crop {$w}x{$h}+{$x1}+{$y1} -auto-orient +repage {$small}");
        //echo '<br />'. $com;
        //exec($com = self::command('convert') ." -size {$oWidth}x{$oHeight} {$small} -thumbnail {$oWidth}x{$oHeight} -gravity center -crop {$x1}x{$y1}+{$w}+{$h} -auto-orient +repage {$small}");
        //exec($com = self::command('convert') ." -size {$oWidth}x{$oHeight} {$small} -thumbnail {$oWidth}x{$oHeight} -gravity center -crop {$x1}x{$y1}+{$w}+{$h} -auto-orient +repage {$small}");
        exec($com = self::command('mogrify') ." -resize {$width}x{$height} $small");
        //echo '<br />'. $com;
    }


}

?>
