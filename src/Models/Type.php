<?php
/**
 * Tpe.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The enum class representing map types
*
*  @author Aleksas Legačinskas
*/
abstract class Type
{
    const Unknown = -1;
    const Race = 0;
    const Platform = 1;
    const Puzzle = 2;
    const Crazy = 3;
    const Shortcut = 4;
    const Stunts = 5;
    const Script = 6;
}