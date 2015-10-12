<?php

/*
Zelda 2 map dumper

This program extracts the map data from a "Zelda II - The Adventure of Link" rom file, and creates .png-images of these maps. 

The map locations defined are the locations for the original game. But the end-point may have to be edited if dumping map data from hacked versions of the game. (Which was the original intent of this program from the beginning).
*/

# Map locations in ROM
$startMap0 = "506C"; # West Hyrule
$endMap0 = "538D";

$startMap1 = "665C"; # Death Mountain
$endMap1 = "6943";

$startMap2 = "9056"; # East Hyrule
$endMap2 = "9370";

$startMap3 = "A65C"; # Maze Island
$endMap3 = "A943";

# Check input file
if ( file_exists($argv[1])) {
	$romFile = $argv[1];
}
else {
	die("No such file: $argv[1]");
}

# Source images
$img_town = @imagecreatefrompng("tiles/town.png");
$img_cave = @imagecreatefrompng("tiles/cave.png");
$img_palace = @imagecreatefrompng("tiles/palace.png");
$img_bridge = @imagecreatefrompng("tiles/bridge.png");
$img_desert = @imagecreatefrompng("tiles/desert.png");
$img_grass = @imagecreatefrompng("tiles/grass.png");
$img_forest = @imagecreatefrompng("tiles/forest.png");
$img_swamp = @imagecreatefrompng("tiles/swamp.png");
$img_graveyard = @imagecreatefrompng("tiles/graveyard.png");
$img_road = @imagecreatefrompng("tiles/road.png");
$img_lava = @imagecreatefrompng("tiles/lava.png");
$img_mountain = @imagecreatefrompng("tiles/mountain.png");
$img_water = @imagecreatefrompng("tiles/water.png");
$img_water_walk = @imagecreatefrompng("tiles/water_walk.png");
$img_rock = @imagecreatefrompng("tiles/rock.png");
$img_spider = @imagecreatefrompng("tiles/spider.png");
$img_error = @imagecreatefrompng("tiles/error.png");

# Grid size of the map
$cols = 64; # All maps are max 64 tiles wide
$rows = 75; # Some maps are smaller than 75, but none is bigger

# Pixel size of the tiles
$width = 16;
$height = 16;

# Main loop
for($map = 0; $map < 4; $map++) {
	switch($map) {
                case "0":
			$start = $startMap0;
			$end = $endMap0;
			break;
                case "1":
			$start = $startMap1;
			$end = $endMap1;
			break;
                case "2":
			$start = $startMap2;
			$end = $endMap2;
			break;
                case "3":
			$start = $startMap3;
			$end = $endMap3;
			break;
	}

	# Calculate the number of bytes to read
	$length = hexdec($end)-hexdec($start);

	# Read mapdata from ROM-file
	$handle = fopen($romFile,'r');
	fseek($handle,hexdec("$start"));
	$mapData = fread($handle, $length);

	# Convert mapData to string
	$mapString = (bin2hex("$mapData"));

	# Create new blank image
	$image = imagecreatetruecolor($width * $cols, $height * $rows);

	# Read one character at a time from mapstring
	# Extract tiletype and number of tiles
	# Print on map
	$posx = 0;
	$posy = 0;
	for ($mapByte = 0; $mapByte < strlen($mapString); $mapByte+=2) {
		$tileType = $mapString[$mapByte+1];
		$numberOfTiles = hexdec($mapString[$mapByte])+1;
		for($t = 0; $t < $numberOfTiles ; $t++) {
			switch($tileType) {
			case "0":
				imagecopy($image, $img_town, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "1":
				imagecopy($image, $img_cave, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "2":
				imagecopy($image, $img_palace, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "3":
				imagecopy($image, $img_bridge, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "4":
				imagecopy($image, $img_desert, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "5":
				imagecopy($image, $img_grass, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "6":
				imagecopy($image, $img_forest, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "7":
				imagecopy($image, $img_swamp, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "8":
				imagecopy($image, $img_graveyard, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "9":
				imagecopy($image, $img_road, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "a":
				imagecopy($image, $img_lava, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "b":
				imagecopy($image, $img_mountain, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "c":
				imagecopy($image, $img_water, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "d":
				imagecopy($image, $img_water_walk, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "e":
				imagecopy($image, $img_rock, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			case "f":
				imagecopy($image, $img_spider, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			default :
				imagecopy($image, $img_error, $posx*$width, $posy*$height, 0, 0, $width, $height);
				break;
			}
			$posx++;
			if($posx >= $cols) {
				$posx = 0;
				$posy++;
			}
			if($posy >= $rows) {
				break;
			}
		}
	}
	# Write image to disk
	$mapname = "map".$map.".png";
	imagepng($image,$mapname); 
	fclose($handle);

} # End main loop

# Clean memory
imagedestroy($image);
imagedestroy($img_town);
imagedestroy($img_cave);
imagedestroy($img_palace);
imagedestroy($img_desert);
imagedestroy($img_grass);
imagedestroy($img_forest);
imagedestroy($img_swamp);
imagedestroy($img_graveyard);
imagedestroy($img_road);
imagedestroy($img_lava);
imagedestroy($img_mountain);
imagedestroy($img_water);
imagedestroy($img_water_walk);
imagedestroy($img_rock);
imagedestroy($img_spider);
imagedestroy($img_error);

?>
