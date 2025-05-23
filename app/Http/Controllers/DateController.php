<?php

namespace App\Http\Controllers;

// Removed: use Illuminate\Http\Request; // Not used

class DateController extends Controller
{
    /**
     * Calculates age in various formats.
     * Note: Uses global setlocale() and ngettext(), which might have side effects
     * and dependencies (gettext extension). Consider Carbon for a more robust solution.
     *
     * @param \DateTime $born
     * @param string $format
     * @return string|int
     */
    public static function ageCalculator(\DateTime $born, string $format = 'full') // Added \ for global DateTime and string type hint for format
    {
        //set current date
        $now = new \DateTime; // Added \ for global DateTime
        //get differ between born date and current date
        $diff = $now->diff($born);
        
        $total_days = $diff->days;
        $total_months = ($diff->y * 12) + $diff->m;
        $total_years = $diff->y;

        //setup of localization if you want to use another language, PHP will translate it
        // Warning: setlocale can have process-wide effects.
        setlocale(LC_ALL, 'fr_FR'); 
        
        $age = ''; // Initialize $age
        //preparing format as on requested by second parameter
        switch($format){
            case 'full':
                $age_parts = [];
                if ($diff->y > 0) {
                    $age_parts[] = $diff->y . ngettext(" year", " years", $diff->y);
                }
                if ($diff->m > 0) {
                    $age_parts[] = $diff->m . ngettext(" month", " months", $diff->m);
                }
                if ($diff->d > 0) {
                    $age_parts[] = $diff->d . ngettext(" day", " days", $diff->d);
                }
                if (count($age_parts) > 1) {
                    $last_part = array_pop($age_parts);
                    $age = implode(', ', $age_parts) . ' and ' . $last_part;
                } elseif (count($age_parts) === 1) {
                    $age = $age_parts[0];
                } else {
                    $age = "0 days"; // Or some other default for same day
                }
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
                $age = $total_months; // Returns int
                break;
            case 'd':
                $age = $total_days; // Returns int
                break;
            case 'y':
                $age = $total_years; // Returns int
                break;
            default:
                // This default case tries to format a string like "%Y, %M and %d"
                // It's complex and relies on specific format strings.
                $age = str_replace(
                    ['%y', '%m', '%d'],
                    [$diff->y, $diff->m, $diff->d],
                    str_replace(
                        ['%Y', '%M', '%D'],
                        [
                            $diff->y . ngettext(" year", " years", $diff->y),
                            $diff->m . ngettext(" month", " months", $diff->m),
                            $diff->d . ngettext(" day", " days", $diff->d)
                        ],
                        $format
                    )
                );
                break;
        }
        return $age;
    }
}
