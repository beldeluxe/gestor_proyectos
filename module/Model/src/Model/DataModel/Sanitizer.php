<?php

namespace Model\DataModel;

class Sanitizer
{

    const INPUT_CLEAN_SYMBOL  = 1;
    const INPUT_CLEAN_QUOTE   = 2;
    const INPUT_CLEAN_SLASH   = 4;
    const INPUT_CLEAN_SLUG    = 7; // (4 | 2 | 1 )
    const INPUT_CLEAN_SQL     = 8;
    const INPUT_CLEAN_URL     = 11;// (8 | 2 | 1 )
    const INPUT_CLEAN_SQL_INJ = 15;// (8 | 4 | 2 | 1 )
    const INPUT_CLEAN_PATH    = 16;
    const INPUT_CLEAN_DATE    = 11;// (8 | 2 | 1 )
    const INPUT_CLEAN_ALL     = 31;// (16 | 8 | 4 | 2 | 1 )
    const INPUT_CLEAN_INT     = 32;

    public static function stripTags($input) {
        
        $allowed = '<div><span><pre><p><br><hr><hgroup><h1><h2><h3><h4><h5><h6>
            <ul><ol><li><dl><dt><dd><strong><em><b><i><u>
            <img><a><abbr><address><blockquote><area><audio><video>
            <fieldset><label><input><textarea>
            <caption><table><tbody><td><tfoot><th><thead><tr>';

        return strip_tags($input, $allowed);

    }
   
    public static function inputReplaceSpecialChars( $input, $cleanFlags = Sanitizer::INPUT_CLEAN_SQL_INJ ) {

        $pattern = array();

        if ($cleanFlags & Sanitizer::INPUT_CLEAN_SYMBOL) {
            $pattern = array_merge($pattern,array("!", "$", "%", "&", "(", ")", "<", ">", ";", "="));
        }

        if ($cleanFlags & Sanitizer::INPUT_CLEAN_QUOTE) {
            $pattern = array_merge($pattern,array("`", "’", "'", '"'));
        }

        if ($cleanFlags & Sanitizer::INPUT_CLEAN_SLASH) {
            $pattern = array_merge($pattern,array("/","\\"));
        }

        if ($cleanFlags & Sanitizer::INPUT_CLEAN_SQL)   {
            $pattern = array_merge($pattern,array("CREATE", "ALTER", "DROP", "TRUNCATE", "INSERT", "UPDATE", "DELETE", "SELECT", "UNION", "FROM", "WHERE", "REPLACE", "FUNCTION", "TABLE", "COLUMN", "ROW", "DATABASE"));
        }

        if ($cleanFlags & Sanitizer::INPUT_CLEAN_PATH)   {
            $pattern = array_merge($pattern,array("http://","https://","ftp://","&&","../"));
        }

        if ($cleanFlags == Sanitizer::INPUT_CLEAN_URL)   {
            $pattern = array_merge($pattern,array(" "));
        }

        if ($cleanFlags == Sanitizer::INPUT_CLEAN_SLUG)   {
            $pattern = array_merge($pattern,array(",",";",":","{","}","|"));
        }

        return str_replace($pattern, "", $input); 

    }

    public static function sanitizeIntInput( $input ) 
    {
        if (empty($input)) return 0;
        return intval($input);
    }    

    public static function sanitizeFloatInput( $input ) 
    {
        if (empty($input)) return floatval(0);
        return floatval($input);
    }    

    public static function sanitizeInput( $input, $maxLength = 0, $cleanFlags = Sanitizer::INPUT_CLEAN_SQL_INJ, $trim = true ) 
    {

        if (empty($input)) return '';

        if ($cleanFlags==Sanitizer::INPUT_CLEAN_INT) {
            return Sanitizer::sanitizeIntInput($input);
        }

        if ($trim) {
            $input = trim( $input );
        }

        if ($maxLength) {
            $input = substr( $input, 0, $maxLength );
        }

        $input = Sanitizer::inputReplaceSpecialChars($input,$cleanFlags);

        return $input;
    }

    public static function sanitizeDateInput( $input )
    {
        return Sanitizer::sanitizeInput( $input, 10, Sanitizer::INPUT_CLEAN_DATE );
    }

    public static function sanitizeFnameInput( $input )
    {
        return Sanitizer::sanitizeInput( $input, 0,  Sanitizer::INPUT_CLEAN_PATH );
    }

    public static function filterFormParams($array){
        foreach ($array as $key=>$value){
            $array[$key] = self::sanitizeInput($value->getValue());
        }
        return $array;
    }

    public static function replaceChars ($cadena) {
        $originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕüÜ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRruU';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }

    public static function slugFromString($string) {

        $string = Sanitizer::sanitizeInput( $string, 0,  Sanitizer::INPUT_CLEAN_SLUG );
        $string = str_replace(array(" ","."), "-", $string);
        $string = preg_replace('/-+/', '-', $string); 

        // transliterate
        $string = strtolower($string);
        $string = Sanitizer::replaceChars($string);
        $string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }

    public static function onlyLettersAndNumber($string, $onlyLetters = false){
        $string = Sanitizer::replaceChars($string);
        if ($onlyLetters) $pattern = "/[^a-zA-Z\s]+/";
        else $pattern = "/[^a-zA-Z0-9\s]+/";
        $result = preg_replace($pattern, "", $string);
        return $result;
    }

}
