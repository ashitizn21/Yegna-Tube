<?php

class FormInputSanitizer 
{
    public static function sanitizeNameInput($input)
    {
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);
        $input = strtolower($input);
        $input = ucfirst($input);

        return $input;
    }
    public static function sanitizeUsernameInput($input)
    {
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);

        return $input;
    }
    public static function sanitizePasswordInput($input)
    {
        $input = strip_tags($input);

        return $input;
    }
    public static function sanitizeEmailInput($input)
    {
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);

        return $input;
    }
}


?>