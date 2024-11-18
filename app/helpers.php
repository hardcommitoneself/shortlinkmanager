<?php

if (!function_exists('fix_withdraw_format')) {
    function fix_withdraw_format($withdraw): string
    {
        preg_match_all('/(?P<price>((?:\d{1,3}[,\.]?)+\d{2}))\sfor\s(?P<method>(.*?))\swithdrawals/', $withdraw, $output_array, PREG_SET_ORDER);

        if($output_array){
            foreach($output_array as $val){
                $output[] = '<b>'.$val['method'].':</b> $'.number_format($val['price'], 2, '.', '');
            }

            sort($output);
            $withdraw = implode(', ',$output);
        }

        else {

            preg_match_all('/(?P<method>(.*?))[\t]\$?(?P<price>((?:\d{1,4}[,\.]?)+\d{2}))/', $withdraw, $output_array, PREG_SET_ORDER);

            if($output_array){
                foreach($output_array as $val){
                    $output[] = '<b>'.trim($val['method']).':</b> $'.number_format($val['price'], 2, '.', '');
                }

                sort($output);
                $withdraw = implode(', ',$output);
            }
        }

        $withdraw = 'Test';

        return $withdraw;
    }
}