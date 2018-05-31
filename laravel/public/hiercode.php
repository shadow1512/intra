<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 31.05.2018
 * Time: 10:01
 */

define('BASE_ALPHA', 26);
define('CODE_LENGTH', 2);

class HierCode
{
    var $length;

    var $digit_to_char = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z');

    var $char_to_digit = array('A' => '1',  'B' => '2',  'C' => '3',  'D' => '4',
        'E' => '5',  'F' => '6',  'G' => '7',  'H' => '8',
        'I' => '9',  'J' => '10', 'K' => '11', 'L' => '12',
        'M' => '13', 'N' => '14', 'O' => '15', 'P' => '16',
        'Q' => '17', 'R' => '18', 'S' => '19', 'T' => '20',
        'U' => '21', 'V' => '22', 'W' => '23', 'X' => '24',
        'Y' => '25', 'Z' => '26');

    var $value;

    function HierCode($length)
    {
        $this->length = $length;
    }
//------------------------------------------------------------------------------
    function setValue($value)
    {
        if(!((strlen($value)) % ($this->length)))
        {
            $this->value = $value;
            return true;
        }
        else
        {
            $this->value = '';
            return false;
        }
    }
//------------------------------------------------------------------------------
    function getValue()
    {
        return $this->value;
    }
//------------------------------------------------------------------------------
    function getArrayFromString($string)
    {
        $result = array();
        for($i = 0; $i < strlen($string); $i++)
            $result[$i] = substr($string, $i, 1);
        return $result;
    }
//------------------------------------------------------------------------------
    function splitCode()
    {
        if($this->value)
        {
            $source_array = $this->getArrayFromString($this->value);
            $result_array = array_chunk($source_array, $this->length);
            return $result_array;
        }
        else
            return false;
    }
//------------------------------------------------------------------------------
    function generateNumericCode($subcode)
    {
        $result = 0;
        for($i = 0; $i < $this->length; $i++)
        {
            $num = pow(100, $this->length - $i - 1) * $this->char_to_digit[$subcode[$i]];
            $result += $num;
        }
        return $result;
    }
//------------------------------------------------------------------------------
    function generateCodeFromNumber($number)
    {
        $code = '';
        for($i = $this->length - 1; $i >= 0; $i--)
        {
            $divider = 100;
            $key    = fmod($number, $divider);
            $number = floor($number / $divider);
            if(isset($this->digit_to_char[$key - 1]))
                $code = $this->digit_to_char[$key - 1] . $code;
            else
            {
                if($i != 0)
                {
                    $addition = floor($key / BASE_ALPHA);
                    $value = fmod($key, BASE_ALPHA);
                    $code = $this->digit_to_char[$value - 1] . $code;
                    $number += $addition;
                }
            }
        }
        if(strlen($code) == $this->length)
            return $code;
        else
            return false;
    }
//------------------------------------------------------------------------------
    function getNextCode()
    {
        if($splitted = $this->splitCode())
        {
            $subcode = $splitted[count($splitted) - 1];
            $number  = $this->generateNumericCode($subcode);
            $number ++;
            if($new_subcode = $this->generateCodeFromNumber($number))
            {
                $new_subarray = $this->getArrayFromString($new_subcode);
                $splitted[count($splitted) - 1] = $new_subarray;
                $result = '';
                foreach($splitted as $block)
                {
                    foreach($block as $char)
                        $result .= $char;
                }
                return $result;
            }
            else
                return false;
        }
        else
            return false;
    }
//------------------------------------------------------------------------------
    function getParentCode()
    {
        if($splitted = $this->splitCode())
        {
            $parent_code = '';
            $num = count($splitted);
            if($num > 1)
            {
                $returned_array = array_slice($splitted, 0, $num - 1);
                foreach($returned_array as $subarray)
                {
                    foreach($subarray as $char)
                        $parent_code .= $char;
                }
                return $parent_code;
            }
            else
                return false;
        }
        else
            return false;
    }
//------------------------------------------------------------------------------
    function getRootBranchCodeArray()
    {
        $branch = array();
        $branch[] = $this->getValue();
        while($parent_value = $this->getParentCode())
        {
            $branch[] = $parent_value;
            $this->setValue($parent_value);
        }
        if(count($branch))
            return $branch;
        else
            return false;
    }
}
/******************************************************************************
 ******************************************************************************/
/*$code =& new HierCode(3);
$code->setValue('HBMAAAABA');
echo $code->getValue() . '<br>';
echo $code->getNextCode();*/
?>