<?php

namespace Webreactor\CliArguments;

class ArgumentsParser {

    public $definitions = array();
    public $arguments;

    public function __construct($args = array(), $definitions = array()) {
        $this->arguments = array();
        $this->addDefinitions($definitions);
        $this->parse($args);
    }

    public function addDefinition(ArgumentDefinition $definition) {
        $this->definitions[$definition->name] = $definition;
    }

    public function reset() {
        $this->definitions = array();
    }

    public function addDefinitions($definitions) {
        foreach ($definitions as $definition) {
            $this->addDefinition($definition);
        }
    }

    public function parse($raw_arguments = null) {
        if (!empty($raw_arguments)) {
            $this->raw_arguments = $raw_arguments;
        }
        $this->arguments = $this->extractArguments($this->raw_arguments);
    }

    public function extractArguments($raw_arguments) {
        $arguments = array();
        $cnt = count($raw_arguments);
        for ($position = 0; $position < $cnt; $position++) {
            $value = $raw_arguments[$position];
            $definition = $this->findDefinition($value);
            if (!empty($definition)) {
                $arguments[$definition->name][] = $this->parseArgument($definition, $position, $raw_arguments);
            } else {
                $flags = $this->tryMultiflag($value);
                if (!empty($flags)) {
                    foreach ($flags as $key => $values) {
                        foreach ($values as $value) {
                            $arguments[$key][] = $value;
                        }
                    }
                } else {
                    $arguments['_words_'][] = $value;    
                }
            }
        }
        return $arguments;
    }

    private function tryMultiflag($str) {
        if (strlen($str) > 2 && $str[0] === '-' && $str[1] !== '-') {
            $raw_flags = str_split(substr($str, 1));
            $flags = array();
            foreach ($raw_flags as $flag) {
                $definition = $this->findDefinition('-'.$flag);
                if (!empty($definition) && $definition->is_flag) {
                    $flags[$definition->name][] = $definition->default;
                }
            }
            return $flags;
        }
        return array();
    }

    private function parseArgument($definition, &$position, $raw_arguments) {
        if ($definition->is_flag) {
            return $definition->default;
        }
        if (isset($raw_arguments[$position + 1])) {
            $position++;
            return $raw_arguments[$position];
        }
        return $definition->default;
    }

    public function findDefinition($name) {
        foreach ($this->definitions as $definition) {
            if ($name === '--'.$definition->name || $name === '-'.$definition->short) {
                return $definition;
            }
        }
        return null;
    }

    public function getAll() {
        $arguments = array();
        foreach ($this->definitions as $key => $definition) {
            $arguments[$key] = $this->get($key);
        }
        return $arguments;
    }

    public function isEntered($name) {
        return isset($this->arguments[$name]);
    }

    public function getReal($name) {
        if (!isset($this->definitions[$name])) {
            return null;
        }
        $definition = $this->definitions[$name];
        if (isset($this->arguments[$name])) {
            $value = $this->arguments[$name];
        } else {
            return null;
        }
        if ($definition->repeated) {
            return $value;
        }
        return end($value);
    }

    public function get($name) {
        if (!isset($this->definitions[$name])) {
            throw new \Exception("ArgumentParser: Not defined parameter '$name'");
        }
        $value = $this->getReal($name);
        if ($value === null){
            return $this->getDefault($name);
        }
        return $value;
    }

    public function getDefault($name) {
        if (!isset($this->definitions[$name])) {
            throw new \Exception("ArgumentParser: Not defined parameter '$name'");
        }
        $definition = $this->definitions[$name];
        if ($definition->default === null) {
            if ($definition->repeated) {
                return array();
            }
            return null;
        } else {
            $value = $definition->default;
            if ($definition->is_flag) {
                $value = null;
            }
            if ($definition->repeated) {
                return (array)$value;
            }
            return $value;
        }
    }
    
}
