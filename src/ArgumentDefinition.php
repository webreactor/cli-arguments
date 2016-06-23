<?php

namespace Webreactor\CliArguments;

class ArgumentDefinition {
    
    public 
        $name,
        $short,
        $default,
        $is_flag,
        $description,
        $repeated;

    public function __construct($name, $short = '', $description = '', $default = null, $is_flag = false, $repeated = false) {
        $this->name = $name;
        $this->short = $short;
        $this->default = $default;
        $this->is_flag = $is_flag;
        $this->description = $description;
        $this->repeated = $repeated;
    }

}
