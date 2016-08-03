<?php

class Configurable {

    public function __construct($config = []) {
        if (!empty($config)) {
            $this->configure($config);
        }
    }

    public function configure($config) {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }
    }
}