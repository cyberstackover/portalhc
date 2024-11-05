<?php

namespace App;

class Toastr
{
    public $text    = '';
    public $type    = '';

    public static $types = ['success', 'warning', 'info', 'error'];

    public function __construct($type, $text)
    {
        $this->type = $type;
        $this->text = $text;
    }
}
