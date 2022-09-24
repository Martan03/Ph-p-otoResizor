# Ph(p)oto Resizor

## Usage:
First you ned to require ImgResize.php and create new ImgResize variable:
```
require("ImgResize.php");
$imgResize = new ImgResize();
```
And now you can start using 'resize' function to resize your images:
```
$imgResize->resize(string $imgPath,
                   int $widthTo,
                   int $heightTo,
                   string $nameTo = "",
                   string $pathTo = "",
                   bool $crop = false);
```
Parameters of the function:
imgPath:
- path to your image you want to resize
widthTo:
- width you want your image to be resized to
heightTo:
- height you want your image to be resized to
nameTo: 
- name you want your image to be named to
- if not given, image will be named "min-" + original image name
pathTo:
- path you want your image to be saved to
- if not given, image will be placed in root directory
crop:
- set "true" if you want to crop your image, default "false"

### Example of usage:
```
$imgResize->resize("myImage.png", 200, 200, "resizedImage", "resizedImages");
```

**Supported file type:** jpeg, jpg, png
