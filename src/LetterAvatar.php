<?php

namespace YoHang88\LetterAvatar;

use Intervention\Image\ImageManager;

class LetterAvatar
{
    /**
     * @var string
     */
    protected $name;


    /**
     * @var string
     */
    protected $name_initials;


    /**
     * @var string
     */
    protected $shape;


    /**
     * @var int
     */
    protected $size;

    /**
     * @var ImageManager
     */
    protected $image_manager;


    public function __construct($name, $shape = 'circle', $size = '48')
    {
        $this->setName($name);
        $this->setImageManager(new ImageManager());
        $this->setShape($shape);
        $this->setSize($size);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ImageManager
     */
    public function getImageManager()
    {
        return $this->image_manager;
    }

    /**
     * @param ImageManager $image_manager
     */
    public function setImageManager(ImageManager $image_manager)
    {
        $this->image_manager = $image_manager;
    }

    /**
     * @return string
     */
    public function getShape()
    {
        return $this->shape;
    }

    /**
     * @param string $shape
     */
    public function setShape($shape)
    {
        $this->shape = $shape;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }


    /**
     * @return \Intervention\Image\Image
     */
    public function generate()
    {
        $words = $this->break_words($this->name);

        $number_of_word = 1;
        foreach ($words as $word) {

            if ($number_of_word > 2)
                break;

            $this->name_initials .= strtoupper(trim($word[0]));

            $number_of_word++;
        }

        $colors = [
            "#39e0ca", "#28de75", "#279dec", "#9b59b6", "#8554d5", "#07b794", "#15c25f", "#0784d5", "#f84369", "#6b5be4",
            "#facc14", "#f38421", "#fe5240", "#e15ea5", "#95a5a6", "#f39c12", "#ff802c", "#d74132", "#9b54d7", "#7f8c8d",
        ];

        $char_index  = ord($this->name_initials[0]) - 64;
        $color_index = $char_index % 20;
        $color       = $colors[$color_index];


        if ($this->shape == 'circle') {
            $canvas = $this->image_manager->canvas(480, 480);

            $canvas->circle(480, 240, 240, function ($draw) use ($color) {
                $draw->background($color);
            });

        } else {

            $canvas = $this->image_manager->canvas(480, 480, $color);
        }

        $canvas->text($this->name_initials, 240, 240, function ($font) {
            $font->file(__DIR__ . '/fonts/arial-bold.ttf');
            $font->size(220);
            $font->color('#ffffff');
            $font->valign('middle');
            $font->align('center');
        });

        return $canvas->resize($this->size, $this->size);
    }

    public function __toString()
    {
        return (string) $this->generate()->encode('data-url');
    }

    public function break_words($name) {
        $temp_word_arr = explode(' ', $name);
        $final_word_arr = array();
        foreach ($temp_word_arr as $key => $word) {
            if( $word != "" && $word != ",") {
                $final_word_arr[] = $word;
            }
        }
        return $final_word_arr;
    }

}
