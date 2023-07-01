<?php
/**
 * Mode.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The enum class representing map mode types
*
*  @author Aleksas Legačinskas
*/
abstract class Mode
{
    const Unknown = -1;
    const EndMarker = 0;
    const CampaignOld = 1;
    const Puzzle = 2;
    const Retro = 3;
    const TimeAttack = 4;
    const Rounds = 5;
    const InProgress = 6;
    const Campaign = 7;
    const Multi = 8;
    const Solo = 9;
    const Site = 10;
    const SoloNadeo = 11;
    const MultiNadeo = 12;
}