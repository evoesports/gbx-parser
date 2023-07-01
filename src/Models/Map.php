<?php
/**
 * Map.php
 */

namespace ESLKem\GBXParser\Models;

use Intervention\Image\Image as Image;

/**
*  The Map class derived from GBX parent and representing the Map/Track type.
*
*  @author Aleksas Legačinskas
*/
class Map extends GBX
{

    /**
     * @var string $softwareVersion The software version which was used to create the file
     */
    private $softwareVersion;

    /**
     * @var string $uid The unique id for the map object
     */
    private $uid;

    /**
     * @var Environment $environment The environment of the map
     */
    private $environment = Environment::Unknown;

    /**
     * @var string $author The author login of the map
     */
    private $author;

    /**
     * @var string $authorName The author (nick)name of the map
     */
    private $authorName;

    /**
     * @var string $authorZone The zone of the author in the form (World|[Continent]|[Country]|[Optional-Regions...])
     */
    private $authorZone;

    /**
     * @var string $name The name of the map
     */
    private $name;

    /**
     * @var Mode $mode The mode of the map
     */
    private $mode = Mode::Unknown;

    /**
     * @var Mood $mood The mood of the map
     */
    private $mood;

    /**
     * @var Decoration $decoration The decoration of the map
     */
    private $decoration = Decoration::Unknown;

    /**
     * @var string $decorationAuthor The author, who made the decoration of the map (usually Nadeo)
     */
    private $decorationAuthor;

    /**
     * @var int $lightmapCacheUID The unique id used for identifying lightmaps in the cache
     */
    private $lightmapCacheUID;

    /**
     * @var int $lightmapVersion The version of the lightmap
     */
    private $lightmapVersion;

    /**
     * @var string $titlePack Optional titlepack used in making the map
     */
    private $titlePack;

    /**
     * @var int $bronzeTime The bronze medal time in miliseconds
     */
    private $bronzeTime;

    /**
     * @var int $silverTime The silver medal time in miliseconds
     */
    private $silverTime;

    /**
     * @var int $goldTime The gold medal time in miliseconds
     */
    private $goldTime;

    /**
     * @var int $authorTime The author medal time in miliseconds
     */
    private $authorTime;

    /**
     * @var int $displayCost The display cost (earlier - CCs or coppers) of the map
     */
    private $displayCost;

    /**
     * @var boolean $multilap Is the map multilap
     */
    private $multilap;

    /**
     * @var Type $mapType The type of the map
     */
    private $mapType = Type::Unknown;

    /**
     * @var int $authorScore The author score of the map, usually matches $authorTime
     */
    private $authorScore;

    /**
     * @var boolean $simpleEditor Was simple editor used in the making of the map
     */
    private $simpleEditor;

    /**
     * @var boolean $containsGhostBlocks Are ghost blocks present in the map
     */
    private $containsGhostBlocks;

    /**
     * @var int $checkpoints The number of checkopoints in the map
     */
    private $checkpoints;

    /**
     * @var int $laps The number of laps to complete to finish the map in non-timeattack modes
     */
    private $laps;

    /**
     * @var Image $thumbnail The Image object representing the thumbnail of the map
     * 
     * @see http://image.intervention.io Documentation for the package
     */
    private $thumbnail;

    /**
     * @var string $comments The author comments for the map
     */
    private $comments;

    /**
     * @var array $dependencies The dependency array containing names/links for the external items
     */
    private $dependencies;

    /**
     * @var string $mod The optional mod name used in making of the map
     */
    private $mod;

    /**
     * @var boolean $passwordProtected Is the map password-protected
     */
    private $passwordProtected;

    /**
     * Constructor method
     * 
     * @param int $version The version of the GBX file
     * @param array $properties Associative array for the map properties. The keys must match object property names
     */
    public function __construct($version, $properties){
        parent::__construct($version);

        foreach($properties as $key => $value)
            $this->$key = $value;
    }

    /**
     * SoftwareVersion getter method
     * 
     * @return string The software version property
     */
    public function getSoftwareVersion() : string {
        return $this->softwareVersion;
    }

    /**
     * Uid getter method
     * 
     * @return string The uid property
     */
    public function getUid() : string {
        return $this->uid;
    }

    /**
     * Environment getter method
     * 
     * @return Environment The environment property
     */
    public function getEnvironment() : int {
        return $this->environment;
    }

    /**
     * Author getter method
     * 
     * @return string The author property
     */
    public function getAuthor() : string {
        return $this->author;
    }

    /**
     * AuthorName getter method
     * 
     * @return string The authorName property
     */
    public function getAuthorName() : string {
        return $this->authorName;
    }

    /**
     * AuthorZone getter method
     * 
     * @return string The authorZone property 
     */
    public function getAuthorZone() : string {
        return $this->authorZone;
    }

    /**
     * Name getter method
     * 
     * @return string The name property 
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * Mode getter method
     * 
     * @return Mode The mode property 
     */
    public function getMode() : int {
        return $this->mode;
    }

    /**
     * Mood getter method
     * 
     * @return Mood The mood property 
     */
    public function getMood() : int {
        return $this->mood;
    }

    /**
     * Decoration getter method
     * 
     * @return Decoration The decoration property 
     */
    public function getDecoration() : int {
        return $this->decoration;
    }

    /**
     * DecorationAuthor getter method
     * 
     * @return string The decorationAuthor property 
     */
    public function getDecorationAuthor() : string {
        return $this->decorationAuthor;
    }

    /**
     * LightmapCacheUID getter method
     * 
     * @return int The lightmapCacheUID property 
     */
    public function getLightmapCacheUID() : int {
        return $this->lightmapCacheUID;
    }

    /**
     * LightmapVersion getter method
     * 
     * @return int The lightmapVersion property 
     */
    public function getLightmapVersion() : int {
        return $this->lightmapVersion;
    }

    /**
     * TitlePack getter method
     * 
     * @return string The titlePack property 
     */
    public function getTitlePack() : string {
        return $this->titlePack;
    }

    /**
     * BronzeTime getter method
     * 
     * @return int The bronzeTime property 
     */
    public function getBronzeTime() : int {
        return $this->bronzeTime;
    }

    /**
     * SilverTime getter method
     * 
     * @return int The silverTime property 
     */
    public function getSilverTime() : int {
        return $this->silverTime;
    }

    /**
     * GoldTime getter method
     * 
     * @return int The goldTime property 
     */
    public function getGoldTime() : int {
        return $this->goldTime;
    }

    /**
     * AuthorTime getter method
     * 
     * @return int The authorTime property 
     */
    public function getAuthorTime() : int {
        return $this->authorTime;
    }

    /**
     * DisplayCost getter method
     * 
     * @return int The displayCost property 
     */
    public function getDisplayCost() : int {
        return $this->displayCost;
    }

    /**
     * Multilap getter method
     * 
     * @return boolean The multilap property 
     */
    public function getMultilap() : bool {
        return $this->multilap;
    }

    /**
     * MapType getter method
     * 
     * @return Type The mapType property 
     */
    public function getmapType() : int {
        return $this->mapType;
    }

    /**
     * AuthorScore getter method
     * 
     * @return int The authorScore property 
     */
    public function getAuthorScore() : int {
        return $this->authorScore;
    }

    /**
     * SimpleEditor getter method
     * 
     * @return boolean The simpleEditor property 
     */
    public function getSimpleEditor() : bool {
        return $this->simpleEditor;
    }

    /**
     * ContainsGhostBlocks getter method
     * 
     * @return boolean The containsGhostBlocks property 
     */
    public function getContainsGhostBlocks() : bool {
        return $this->containsGhostBlocks;
    }

    /**
     * Checkpoints getter method
     * 
     * @return int The checkpoints property 
     */
    public function getCheckpoints() : int {
        return $this->checkpoints;
    }

    /**
     * Laps getter method
     * 
     * @return int The laps property 
     */
    public function getLaps() : int {
        return $this->laps;
    }

    /**
     * Thumbnail getter method
     * 
     * @return Image The thumbnail property 
     */
    public function getThumbnail() : Image {
        return $this->thumbnail;
    }

    /**
     * Comments getter method
     * 
     * @return string The comments property 
     */
    public function getComments() : string {
        return $this->comments;
    }

    /**
     * Dependencies getter method
     * 
     * @return array The dependencies property 
     */
    public function getDependencies() : array {
        return $this->dependencies;
    }

    /**
     * Mod getter method
     * 
     * @return string The mod property 
     */
    public function getMod() : string {
        return $this->mod;
    }

    /**
     * PasswordProtected getter method
     * 
     * @return boolean The passwordProtected property 
     */
    public function getPasswordProtected() : bool {
        return $this->passwordProtected;
    }
}