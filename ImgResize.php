<?php

class ImgResize
{
    /**
     * Resizes image
     * @param string $img Path to image
     * @param int $w Width to be resized
     * @param int $h Height to be resized
     * @param string $name Optional image file name, otherwise 'min-' + given image name
     * @param string $path Optional path where to save image
     * @param bool $crop Optional crop option
     */
    public function resize(string $img, int $w, int $h, string $name = "", string $path = "", bool $crop = false) : bool
    {
        list($width, $height) = getimagesize($img);
        $origRatio = $width / $height;
        $newRatio = $w / $h;

        if ($crop)
        {
            if ($width > $height)
                $width = ceil($width - ($width * abs($origRatio - $newRatio)));
            else
                $height = ceil($height - ($height * abs($origRatio - $newRatio)));
        }
        else
        {
            if ($newRatio > $origRatio)
                $w = $h * $origRatio;
            else
                $h = $w / $origRatio;
        }

        $src = $this->getSrc($img);
        $dst = $this->getDst($img, $w, $h);

        if (!$src || !$dst) return false;
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        $pathInfo = pathinfo($img);
        $name = ($name == "") ? "min-" . $pathInfo['filename'] : $name;
        $filename = (!empty(trim($path, "/"))) ? trim($path, "/") . "/" : "";
        $filename .= $name . '.' . $pathInfo['extension'];
        echo $filename;
        $this->saveImg($dst, $filename, $pathInfo['extension']);

        return true;
    }

    /**
     * Creates GdImage
     * @param string $img Path to image
     * @return mixed GdImage on success, else false
     */
    private function getSrc(string $img) : GdImage|false
    {
        $ext = pathinfo($img, PATHINFO_EXTENSION);
        return match ($ext) {
            'jpg' => imagecreatefromjpeg($img),
            'jpeg' => imagecreatefromjpeg($img),
            'png' => imagecreatefrompng($img),
            default => false,
        };
    }

    /**
     * Returns black image
     * @param string $img Path to image
     * @param int $w Width of the image
     * @param int $h Height of the image
     * @return mixed GdImage on success, else false
     */
    private function getDst(string $img, int $w, int $h) : GdImage|false
    {
        $ext = pathinfo($img, PATHINFO_EXTENSION);
        $dst = imagecreatetruecolor($w, $h);

        if ($ext == 'png')
        {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        return $dst;
    }

    /**
     * Saves image
     * @param GdImage $dst
     * @param string $name New path with file name and extension
     * @param string $ext Image extension
     */
    private function saveImg(GdImage $dst, string $name, string $ext)
    {
        switch ($ext)
        {
            case 'jpg':
                imagejpeg($dst, $name);
                break;
            case 'jpeg':
                imagejpeg($dst, $name);
                break;
            case 'png':
                imagepng($dst, $name);
                break;
            default:
                break;
        }
    }
}