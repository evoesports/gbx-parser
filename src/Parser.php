<?php
/**
 * Parser.php
 * 
 * @author Aleksas Legačinskas
 */

namespace ESLKem\GBXParser;

use ESLKem\GBXParser\Models\Map;
use ESLKem\GBXParser\Models\GBX;
use ESLKem\GBXParser\Models\Type;
use ESLKem\GBXParser\Models\Mode;
use ESLKem\GBXParser\Models\Decoration;
use ESLKem\GBXParser\Models\Environment;
use ESLKem\GBXParser\Models\Mood;

use Intervention\Image\ImageManagerStatic as Image;

/**
*  The primary parser class for GBX parsing
*
*  This class holds one public Parser::parse() methods which calls internal byte reading methods and reads primitives into a Map (or GBX, if unrecognized) object.
*
*  @link https://wiki.xaseco.org/wiki/GBX The wiki article describing GBX file structure
*/
class Parser
{
    /**
    * The parsing function which reads the specified file and returns the parsed result, temporarilly all the data is stored in a simple array
    *
    * @param string $filename A string containing the GBX file location
    *
    * @return GBX A GBX (or any subclass) object contaning the parsed information 
    */
    public static function parse(string $filename) : ?GBX {
        
        $fileHandle = fopen($filename, 'rb');

        $magicString = self::fetchBytes($fileHandle, 3);

        if($magicString !== "GBX")
            return null;

        $version = self::fetchUInt16($fileHandle);

        //Skip unnecessary compression flags
        if($version >= 3)
            self::fetchBytes($fileHandle, 3);
        if($version >= 4)
            self::fetchBytes($fileHandle);
        
        $classID = self::fetchUInt32($fileHandle);

        if($classID != 0x03043000) //Map class id                     //Unknown/unused class. 
            return new Gbx($version);

        $properties = array();

        if($version >= 6){
            $userDataSize = self::fetchUInt32($fileHandle);
            $headerChunkCount = self::fetchUInt32($fileHandle);
            
            $headerInfos = array();

            for($i = 0; $i < $headerChunkCount; $i++){
                $chunkID = self::fetchUInt32($fileHandle);
                $chunkSize = self::fetchUInt32($fileHandle);
                
                //unset 31st bit in chunkSize
                $chunkSize &= 0x7FFFFFFF;

                array_push($headerInfos, ['id' => $chunkID, 'size' => $chunkSize]);
            }

            foreach($headerInfos as $header){
                switch($header['id']){
                    case 0x03043002:
                        self::readTmDesc($fileHandle, $properties);
                        break;
                    case 0x03043003:
                        self::readCommon($fileHandle, $properties);
                        break;
                    case 0x03043005:
                        self::readCommunity($fileHandle, $properties);
                        break;
                    case 0x03043007:
                        self::readThumbnail($fileHandle, $properties);
                        break;
                    case 0x03043008:
                        self::readAuthor($fileHandle, $properties);
                        break;
                    default:
                        self::fetchBytes($fileHandle, $header['size']);
                }
            }
        }

        $nodeCount = self::fetchUInt32($fileHandle);

        fclose($fileHandle);

        // var_dump($properties);

        return new Map($version, $properties);
    }

    /**
    * Reads the TmDesc chunk of GBX
    *
    * @param resource $fileHandle A stream for the opened file
    * @param array $properties A reference to the temporary property array 
    */
    private static function readTmDesc($fileHandle, array &$properties){
        $version = self::fetchUInt8($fileHandle);
        //TODO: version < 3 check

        self::fetchBool($fileHandle);

        if($version >= 1){
            $properties['bronzeTime'] = self::fetchUInt32($fileHandle);
            $properties['silverTime'] = self::fetchUInt32($fileHandle);
            $properties['goldTime'] = self::fetchUInt32($fileHandle);
            $properties['authorTime'] = self::fetchUInt32($fileHandle);
            
            if($version == 2)
                self::fetchBytes($fileHandle);

            if($version >= 4){
                $properties['displayCost'] = self::fetchUInt32($fileHandle);

                if($version >= 5){
                    $properties['multilap'] = self::fetchBool($fileHandle);
                    
                    if($version == 6)
                        self::fetchBool($fileHandle);

                    if($version >= 7){
                        $type = self::fetchUInt32($fileHandle);

                        switch($type){
                            case 0:
                                $properties['mapType'] = Type::Race;
                                break;
                            case 1:
                                $properties['mapType'] = Type::Platform;
                                break;
                            case 2:
                                $properties['mapType'] = Type::Puzzle;
                                break;
                            case 3:
                                $properties['mapType'] = Type::Crazy;
                                break;
                            case 4:
                                $properties['mapType'] = Type::Shortcut;
                                break;
                            case 5:
                                $properties['mapType'] = Type::Stunts;
                                break;
                            case 6:
                                $properties['mapType'] = Type::Script;
                                break;
                        }

                        if($version >= 9){
                            self::fetchUInt32($fileHandle);

                            if($version >= 10){
                                $properties['authorScore'] = self::fetchUInt32($fileHandle);

                                if($version >= 11){
                                    $editor = self::fetchUInt32($fileHandle);

                                    $properties['simpleEditor'] = ($editor & 0x1) == 1;
                                    $properties['containsGhostBlocks'] = ($editor & 0x2) == 2;

                                    if($version >= 12){
                                        self::fetchBool($fileHandle);

                                        if($version >= 13){
                                            $properties['checkpoints'] = self::fetchUInt32($fileHandle);
                                            $properties['laps'] = self::fetchUInt32($fileHandle);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    /**
    * Reads the Common chunk of GBX
    *
    * @param resource $fileHandle A stream for the opened file
    * @param array $properties A reference to the temporary property array 
    */
    private static function readCommon($fileHandle, array &$properties){
        $version = self::fetchUInt8($fileHandle);

        $lookbackStrings = array();

        $meta = self::fetchMeta($fileHandle, $lookbackStrings);
        $properties['uid'] = $meta[0];
        switch($meta[1]){
            case "Stadium":
                $properties['environment'] = Environment::Stadium;
                break;
            case "Canyon":
                $properties['environment'] = Environment::Canyon;
                break;
            case "Valley":
                $properties['environment'] = Environment::Valley;
                break;
            case "Lagoon":
                $properties['environment'] = Environment::Lagoon;
                break;
            case "Storm":
                $properties['environment'] = Environment::Storm;
                break;
            default:
                $properties['environment'] = Environment::Unknown;
        }
        $properties['author'] = $meta[2];

        $properties['name'] = self::fetchString($fileHandle);

        $kind = self::fetchUInt8($fileHandle);

        switch($kind){
            case 0:
                $properties['mode'] = Mode::EndMarker;
                break;
            case 1:
                $properties['mode'] = Mode::Campaign;
                break;
            case 2:
                $properties['mode'] = Mode::Puzzle;
                break;
            case 3:
                $properties['mode'] = Mode::Retro;
                break;
            case 4:
                $properties['mode'] = Mode::TimeAttack;
                break;
            case 5:
                $properties['mode'] = Mode::Rounds;
                break;
            case 6:
                $properties['mode'] = Mode::InProgress;
                break;
            case 7:
                $properties['mode'] = Mode::Campaign;
                break;
            case 8:
                $properties['mode'] = Mode::Multi;
                break;
            case 9:
                $properties['mode'] = Mode::Solo;
                break;
            case 10:
                $properties['mode'] = Mode::Site;
                break;
            case 11:
                $properties['mode'] = Mode::SoloNadeo;
                break;
            case 12:
                $properties['mode'] = Mode::MultiNadeo;
                break;
            default:
                $properties['mode'] = Mode::Unknown;
        }

        if($version >= 1){
            $properties['passwordProtected'] = self::fetchBool($fileHandle);
            self::fetchString($fileHandle); //password - not used

            if($version >= 2){
                $meta = self::fetchMeta($fileHandle, $lookbackStrings);
                if(strpos($meta[0], "Day") != false){
                    $properties['mood'] = Mood::Day;
                }else if(strpos($meta[0], "Sunrise") != false){
                    $properties['mood'] = Mood::Sunrise;
                }else if(strpos($meta[0], "Sunset") != false){
                    $properties['mood'] = Mood::Sunset;
                }else if(strpos($meta[0], "Night") != false){
                    $properties['mood'] = Mood::Night;
                }else{
                    $properties['mood'] = Mood::Unknown;
                }
                
                switch($meta[1]){
                    case "Stadium":
                        $properties['decoration'] = Decoration::Stadium;
                        break;
                    case "Canyon":
                        $properties['decoration'] = Decoration::Canyon;
                        break;
                    case "Valley":
                        $properties['decoration'] = Decoration::Valley;
                        break;
                    case "Lagoon":
                        $properties['decoration'] = Decoration::Lagoon;
                        break;
                    case "Storm":
                        $properties['decoration'] = Decoration::Storm;
                        break;
                    default:
                        $properties['decoration'] = Decoration::Unknown;
                }

                $properties['decorationAuthor'] = $meta[2];

                if($version >= 3){
                    //map origin
                    self::fetchFloat($fileHandle);
                    self::fetchFloat($fileHandle);

                    if($version >= 4){
                        //map target
                        self::fetchFloat($fileHandle);
                        self::fetchFloat($fileHandle);
                        
                        if($version >= 5){
                            //No 128-bit support in php :)
                            self::fetchUInt64($fileHandle);
                            self::fetchUInt64($fileHandle);

                            if($version >= 6){
                                self::fetchString($fileHandle); //map type
                                self::fetchString($fileHandle); //map style

                                if($version < 8){
                                    self::fetchBool($fileHandle);
                                }else if($version >= 8){
                                    $properties['lightmapCacheUID'] = self::fetchUInt64($fileHandle);
                                    
                                    if($version >= 9){
                                        $properties['lightmapVersion'] = self::fetchUInt8($fileHandle);

                                        if($version >= 11){
                                            $properties['titlePack'] = self::fetchLookbackString($fileHandle, $lookbackStrings);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
    * Reads the Community chunk of GBX
    *
    * @param resource $fileHandle A stream for the opened file
    * @param array $properties A reference to the temporary property array 
    */
    private static function readCommunity($fileHandle, array &$properties){
        //Parser ignores the root node, so let's fake one
        $xmlString = '<?xml version="1.0" encoding="utf-8"?>' .
                        '<wrapper>' . 
                        self::fetchString($fileHandle) . 
                        '</wrapper>';
        
        $service = new \Sabre\Xml\Service();

        $service->elementMap = [
            '' => '\Sabre\Xml\Deserializer\keyValue',
        ];

        $result = $service->parse($xmlString);

        $properties['softwareVersion'] = $result[0]['attributes']['exever'];
        $properties['mod'] = $result[0]['value'][1]['attributes']['mod'];
        $properties['dependencies'] = array();
        foreach($result[0]['value'][4]['value'] as $deps){
            array_push($properties['dependencies'], $deps['attributes']);
        }
    }

    /**
    * Reads the Thumbnail chunk of GBX
    *
    * @param resource $fileHandle A stream for the opened file
    * @param array $properties A reference to the temporary property array 
    */
    private static function readThumbnail($fileHandle, array &$properties){
        $version = self::fetchUInt32($fileHandle);

        if($version != 0){
            $size = self::fetchUInt32($fileHandle);

            self::fetchBytes($fileHandle, strlen('<Thumbnail.jpg>'));
            $imageString = self::fetchBytes($fileHandle, $size);
            $properties['thumbnail'] = Image::make($imageString)->flip("v");
            self::fetchBytes($fileHandle, strlen('</Thumbnail.jpg>'));

            self::fetchBytes($fileHandle, strlen('<Comments>'));
            $properties['comments'] = self::fetchString($fileHandle);
            self::fetchBytes($fileHandle, strlen('</Comments>'));
        }
    }

    /**
    * Reads the Author chunk of GBX
    *
    * @param resource $fileHandle A stream for the opened file
    * @param array $properties A reference to the temporary property array 
    */
    private static function readAuthor($fileHandle, array &$properties){
        $version = self::fetchUInt32($fileHandle);

        self::fetchUInt32($fileHandle); //author version
        self::fetchString($fileHandle); //author login
        $properties['authorName'] = self::fetchString($fileHandle); 
        $properties['authorZone'] = self::fetchString($fileHandle); 
        self::fetchString($fileHandle); //extra info
    }

    /**
    * Fetches a specified number of bytes from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @param int $numBytes The number of bytes to read 
    * @return string The read bytes represented by a string
    */
    private static function fetchBytes($handle, int $numBytes = 1) : string {
        if($numBytes <= 0)
            return '';
        return fread($handle, $numBytes);
    }

    /**
    * Fetches an 8-bit unsigned integer from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return int The read integer
    */
    private static function fetchUInt8($handle) : int {
        return unpack('C', self::fetchBytes($handle))[1];
    }

    /**
    * Fetches a 16-bit unsigned integer from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return int The read integer
    */
    private static function fetchUInt16($handle) : int {
        return unpack('v', self::fetchBytes($handle, 2))[1];
    }
    
    /**
    * Fetches a 32-bit unsigned integer from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return int The read integer
    */
    private static function fetchUInt32($handle) : int {
        return unpack('V', self::fetchBytes($handle, 4))[1];
    }

    /**
    * Fetches a 64-bit unsigned integer from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return int The read integer
    */
    private static function fetchUInt64($handle) : int {
        return unpack('P', self::fetchBytes($handle, 8))[1];
    }

    /**
    * Fetches a 32-bit floating point number from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return float The read float
    */
    private static function fetchFloat($handle) : float {
        return unpack('g', self::fetchBytes($handle, 4))[1];
    }

    /**
    * Fetches a 32-bit boolean from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return boolean The read boolean
    */
    private static function fetchBool($handle) : bool {
        return self::fetchUInt32($handle) === 1;
    }

    /**
    * Fetches a string from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @return string The read integer
    */
    private static function fetchString($handle) : string {
        $length = self::fetchUInt32($handle);
        return self::fetchBytes($handle, $length);
    }

    /**
    * Fetches a lookbackstring from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @param array $lookbackStrings A reference to the lookbackstring array used for the chunk being read 
    * @return string|null The read string
    */
    private static function fetchLookbackString($handle, array &$lookbackStrings) : ?string {
        $index = self::fetchUInt32($handle);
        if(($index & 0xc0000000) === 0){
            $realIndex = $index & 0x3fffffff;
            if($realIndex === 0){
                // ?
            }else{
                return $lookbackStrings[$realIndex - 1];
            }
        }else if(($index & 0x80000000) !== 0 || ($index & 0x40000000) !== 0){
            $realIndex = $index & 0x3fffffff;
            if($realIndex === 0){
                $string = self::fetchString($handle);
                array_push($lookbackStrings, $string);
                return $string;
            }else{
                return $lookbackStrings[$realIndex - 1];
            }
        }

        return null;
    }

    /**
    * Fetches a meta structure from the data stream
    *
    * @param resource $handle A stream for the opened file
    * @param array $lookbackStrings A reference to the lookbackstring array used for the chunk being read 
    * @return array The read array of three strings
    */
    private static function fetchMeta($handle, array &$lookbackStrings) : array {
        if(count($lookbackStrings) === 0){
            self::fetchUInt32($handle); //version
        }

        $meta = array();

        for($i = 0; $i < 3; $i++){
            array_push($meta, self::fetchLookbackString($handle, $lookbackStrings));
        }

        return $meta;
    }
}