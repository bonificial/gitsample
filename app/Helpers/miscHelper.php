<?php

    //Class has miscellaneous functions
class miscHelper
{
    //Function Below generates a random string, takes optional parameters to state the string length, or type required. Default is 6 and MIX respectively.
    public function generateRandomString($length = 6,$type='MIX') {

        if($type == 'MIX'){
            $characters = '23456789abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        }elseif($type == 'NUMBERS'){
            $characters = '123456789';
        }elseif ($type == 'LETTERS'){
            $characters = 'abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}