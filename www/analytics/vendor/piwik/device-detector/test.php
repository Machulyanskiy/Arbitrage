<?php
/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */

require __DIR__ . '/vendor/autoload.php';

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

if(isset($_GET['ua'])) {
    $userAgent = $_GET['ua'];
} else {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
}

DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);

$result = DeviceDetector::getInfoFromUserAgent($userAgent);

echo '<form><input type="text" name="ua" /><input type="submit" /></form>';

echo "<pre>";

echo Spyc::YAMLDump($result, 2, 0);
echo "</pre>";
