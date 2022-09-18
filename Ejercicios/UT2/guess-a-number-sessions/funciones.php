<?php
    function valid_input($variable) {
        if ($variable === null || $variable === false) {
            return false;
        }

        return true;
    }
?>