<?php

namespace App\Services\Cart;

/**
 * Class ConvertWeightServices
 * @package App\Services
 */
class ConvertWeightServices
{
    public function ConvertWeight($weight,$convertType) {

        if($convertType == 'KG_TO_GRAM')
        {
            $getWeightCal =   $this->convertKgToGRAM($weight);
        }
        if($convertType == 'GRAM_TO_KG')
        {
           $getWeightCal =  $this->convertGramToKg($weight);
        }

        return $getWeightCal;
    }
    private function convertKgToGRAM($weightKg)
    {
        $getGram = $weightKg*1000;
        return $getGram;
    }
    private function convertGramToKg($weightGram)
    {
        $getKg = $weightGram/1000;
        return $getKg;
    }
}
