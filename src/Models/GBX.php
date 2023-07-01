<?php
/**
 * GBX.php
 */

namespace ESLKem\GBXParser\Models;

/**
*  The main GBX file wrapper
*
*  All GBX types share a common GBX version which is stored in this class. Other information is GBX type-specific and is stored in derived classes
*
*  @author Aleksas Legačinskas
*/
class GBX
{
    /** 
     * @var int $version The version of the GBX format 
     */
    private $version;

    /**
     * Constructor method
     * 
     * @param int $version The version
     */
    public function __construct(int $version){
        $this->version = $version;
    }

    /**
     * Version getter method
     * 
     * @return int The GBX version property
     */
    public function getVersion() : int{
        return $this->version;
    }
}