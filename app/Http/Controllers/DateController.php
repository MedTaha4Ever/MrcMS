<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DateController extends Controller
{
    public static function ageCalculator(DateTime $born, $format = 'full'){
        //set current date
        $now = new DateTime;
        //get differ between born date and current date
        $diff = $now->diff($born);
        
        $total_days = $diff->days;
        $total_months = ($diff->y * 12) + $diff->m;
        $total_years = $diff->y;
        //setup of localization if you want to use another language, PHP will translate it
        setlocale(LC_ALL, 'fr_FR');
        
        //preparing format as on requested by second parameter
        switch($format){
            case 'full':
                $age = ($d = $diff->d) ? ' and '. $d . ngettext(" day", " days", $d) : '';
                $age = ($m = $diff->m) ? ($age ? ', ' : ' and '). $m . ngettext(" month", " months", $m).$age : $age;
                $age = ($y = $diff->y) ? $y . ngettext(" year", " years", $y).$age  : $age;
                break;
            case 'M':
                $age = $total_months . ' ' . ngettext(" month", " months", $total_months);
                break;
            case 'D':
                $age = $total_days . ' ' . ngettext(" day", " days", $total_days);
                break;
            case 'Y':
                $age = $total_years . ' ' . ngettext(" year", " years", $total_years);
                break;
            case 'm':
                $age = $total_months;
                break;
            case 'd':
                $age = $total_days;
                break;
            case 'y':
                $age = $total_years;
                break;
            default:
                $age = str_replace(array('%y', '%m', '%d'), 
                                   array($diff->y, $diff->m, $diff->d), 
                                   str_replace(array('%Y', '%M', '%D'), 
                                               array($diff->y . ngettext(" year", " years", $diff->y), $diff->m . ngettext(" month", " months", $diff->m), $diff->d . ngettext(" day", " days", $diff->d)), 
                                               $format));
                break;
        }
        return $age;
    }
}
