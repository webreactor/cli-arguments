<?php

namespace Webreactor\CliArguments;

class ArgumentsParser {

    public $definitions;
    public $arguments;

    public function __construct($args = array(), $definitions = array()) {
        $this->arguments = array();
        $this->parse($args);
        $this->definitions = $definitions;
    }

    public function addDefinition($name, $short_name, $default = false, $repeated = false, $description = '') {
        $this->definitions[$name] = array(
            'name' => $name,
            'short_name' => $short_name,
            'repeated' => $repeated,
            'description' => $description,
            'default' => $default,
        );
    }

    public function addDefinitions($definitions) {
        foreach ($definitions as $definition) {
            $this->setDefinition(
                $description['name'],
                $description['short_name'],
                $description['repeated'],
                $description['description'],
                $description['default']
            );
        }
    }

    public function parse($args) {
        $this->arguments = $this->extractArguments($args);
    }

    public function getAll() {
        $arguments = array();
        foreach ($this->definitions as $key => $definition) {
            $arguments[$key] = $this->get($key);
        }
        return $arguments;
    }

    public function getReal() {
        $arguments = array();
        foreach ($this->definitions as $key => $definition) {
            $value = $this->tryGetArgument($key);
            if ($value !== null) {
                $arguments[$key] = $value;
            }
        }
        return $arguments;
    }

    public function tryGetArgument($name) {
        if (!isset($this->definitions[$name])) {
            return null;
        }
        $definition = $this->definitions[$name];
        if (isset($this->arguments[$name])) {
            $value = $this->arguments[$name];
        } else {
            if (isset($this->arguments[$definition['short_name']])) {
                $value = $this->arguments[$definition['short_name']];
            } else {
                return null;
            }
        }
        if ($definition['repeated']) {
            return $value;
        }
        return $value[0];
    }

    public function get($name) {
        if (!isset($this->definitions[$name])) {
            throw new \Exception("ArgumentParser: Not defined parameter '$name'");
        }
        $value = $this->tryGetArgument($name);
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
        if ($definition['default'] === null) {
            if ($definition['repeated']) {
                return array();
            }
            return null;
        } else {
            if ($definition['repeated']) {
                return (array)$definition['default'];
            }
            return $definition['default'];
        }
    }

    public function extractArguments($raw) {
        $length = count($raw);
        $data = array();
        $state = 'begin';
        $key = false;

        for ($i = 0; $i < $length; $i++) {
            $word = $raw[$i];
            $keys = $this->parseKeys($word);
            if ($keys !== false) {
                if (count($keys) > 1) {
                    foreach ($keys as $key) {
                        $data[$key][] = true;
                    }
                    $key = false;
                } else {
                    $key = $keys[0];
                    // if next is another key or current is last one
                    // treat current as boolea
                    if (isset($raw[$i + 1])) {
                        $next = $raw[$i + 1];
                        if ($this->parseKeys($next) !== false) {
                            $data[$key][] = true;
                            $key = false;
                        }
                    } else {
                        $data[$key][] = true;
                        $key = false;
                    }
                }
            } else {
                if ($key !== false) {
                    $data[$key][] = $word;
                    $key = false;
                } else {
                    $data['_words_'][] = $word;
                }
            }
        }
        return $data;
    }

    public function parseKeys($word) {
        $length = strlen($word);
        if ($length > 2 && $word[0] == '-' && $word[1] == '-') {
            return array(substr($word, 2));
        }
        if ($length > 1 && $word[0] == '-' && $word[1] != '-') {
            return str_split(substr($word, 1));
        }
        return false;
    }
    
}
