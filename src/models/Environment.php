<?php
/**
 * Environment.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The enum class representing Environment types
*
*  Currently supported are four Trackmania environments and one Shootmania environment
*
*  @author Aleksas Legačinskas
*/
abstract class Environment
{
    const Unknown = -1;
    const Stadium = 0;
    const Canyon = 1;
    const Valley = 2;
    const Lagoon = 3;
    const Storm = 4;
}