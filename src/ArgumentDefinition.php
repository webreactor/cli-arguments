<?php

namespace Reactor\CliArguments;

class ArgumentDefinition {
    
    public 
        $name,
        $short,
        $default,
        $is_flag,
        $repeated,
        $description;

    public function __construct($name, $short = '', $default = null, $is_flag = false, $repeated = false, $description = '') {
        $this->name = $name;
        $this->short = $short;
        $this->default = $default;
        $this->is_flag = $is_flag;
        $this->repeated = $repeated;
        $this->description = $description;
    }

}
