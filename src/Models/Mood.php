<?php
/**
 * Mood.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The enum class representing the mood setting of the map
*
*  Contains all four default moods: Day, Night, Sunrise and Sunset. Different sizes of bases (e.g. 64x64) are not considered
*
*  @author Aleksas Legačinskas
*/
abstract class Mood
{
    const Unknown = -1;
    const Day = 0;
    const Sunset = 1;
    const Night = 2;
    const Sunrise = 3;
}