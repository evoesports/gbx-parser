<?php 

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author Aleksas Legačinskas
*/
namespace ESLKem\GBXParser\Tests;

use PHPUnit\Framework\TestCase;
use ESLKem\GBXParser\Parser;
use ESLKem\GBXParser\Models\Map;
use ESLKem\GBXParser\Models\GBX;
use ESLKem\GBXParser\Models\Type;
use ESLKem\GBXParser\Models\Mode;
use ESLKem\GBXParser\Models\Decoration;
use ESLKem\GBXParser\Models\Environment;
use ESLKem\GBXParser\Models\Mood;
use Intervention\Image\ImageManagerStatic as Image;

class ParserTest extends TestCase
{

    private $mapFilename = __DIR__ . '/spmWeekly - Christmas Chrisis..Map.Gbx';

    public function testParserShouldIdentifyMap(){

        $result = Parser::parse($this->mapFilename);

        $this->assertInstanceOf('ESLKem\GBXParser\Models\Map', $result);
    }

    public function testParsedMapShouldContainAllData(){
        $map = Parser::parse($this->mapFilename);

        $this->assertEquals(6, $map->getVersion());

        $this->assertEquals('3.3.0', $map->getSoftwareVersion());

        $this->assertEquals('gVy2t2Al4zE05XIHu1BEiXRMAYa', $map->getUid());

        $this->assertEquals(Environment::Stadium, $map->getEnvironment());

        $this->assertEquals('erizel', $map->getAuthor());

        $this->assertEquals('$f00BB$ffferizel', $map->getAuthorName());

        $this->assertEquals('World|Europe|Norway', $map->getAuthorZone());

        $this->assertEquals('$s$fffspmWeekly - $f01Christmas $fffChrisis$f01.', $map->getName());

        $this->assertEquals(Mode::Multi, $map->getMode());

        $this->assertEquals(Mood::Day, $map->getMood());

        $this->assertEquals(Decoration::Stadium, $map->getDecoration());

        $this->assertEquals('Nadeo', $map->getDecorationAuthor());

        $this->assertEquals(0x0DC9B898B9C148D8, $map->getLightmapCacheUID());

        $this->assertEquals(6, $map->getLightmapVersion());

        $this->assertEquals('esl_comp@lt_forever', $map->getTitlePack());

        $this->assertEquals(364000, $map->getBronzeTime());

        $this->assertEquals(291000, $map->getSilverTime());

        $this->assertEquals(257000, $map->getGoldTime());

        $this->assertEquals(242166, $map->getAuthorTime());

        $this->assertEquals(9115, $map->getDisplayCost());

        $this->assertEquals(true, $map->getMultilap());

        $this->assertEquals(Type::Race, $map->getMapType());

        $this->assertEquals(242166, $map->getAuthorScore());

        $this->assertEquals(false, $map->getSimpleEditor());

        $this->assertEquals(true, $map->getContainsGhostBlocks());

        $this->assertEquals(38, $map->getCheckpoints());

        $this->assertEquals(1, $map->getLaps());

        $this->assertNotNull($map->getThumbnail());

        $this->assertEquals('', $map->getComments());

        $deps = [
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Advert.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Advert.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_U.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_U.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_D.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_D.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_DL.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_DL.png"],
            ['file' => "Skins\Stadium\CircuitScreen\WhiteLeft.webm"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_R.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_R.png"],
            ['file' => "Skins\Stadium\CircuitScreen\WhiteRight.webm"],
            ['file' => "Skins\Stadium\Inflatable\White.zip"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_L.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_L.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_DR.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_DR.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_AlternativeRoute.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_AlternativeRoute.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_UL.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_UL.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_UR.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_UR.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_DriveBackwards.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_DriveBackwards.png"],
            ['file' => "Skins\Any\Advertisement\U6gek3d.jpg", 'url' => "http://i.imgur.com/U6gek3d.jpg"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_UTurn_R.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_UTurn_R.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Loop_L.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Loop_L.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Respawn.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Respawn.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_GPS.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_GPS.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Gift1.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Gift1.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Gift2.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Gift2.png"],
            ['file' => "Skins\Any\Advertisement\CS_SpmWeekly_Gift3.png", 'url' => "http://maniacdn.net/toffe/spamweekly/CS_SpmWeekly_Gift3.png"],
            ['file' => "Skins\Stadium\Mod\MXmas15.zip", 'url' => "http://ac.ozontm.de/download/MXmas15.zip"],
            ['file' => "Skins\Models\StadiumCar\NOR.zip"],
            ['file' => "Skins\Models\StadiumCar\Mastersoftyping Carskin TM2.zip"],
            ];

        $this->assertEquals($deps, $map->getDependencies());

        $this->assertEquals('MXmas15', $map->getMod());

        $this->assertEquals(false, $map->getPasswordProtected());

    }
  
}