<?php
/**
 * Decoration.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The enum class representing Decoration types
*
*  Currently supported are four Trackmania environments and one Shootmania environment
*
*  @author Aleksas Legačinskas
*/
abstract class Decoration
{
    const Unknown = -1;
    const Stadium = 0;
    const Canyon = 1;
    const Valley = 2;
    const Lagoon = 3;
    const Storm = 4;
}