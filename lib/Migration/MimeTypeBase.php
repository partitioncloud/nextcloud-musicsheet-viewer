<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: WARP <development@warp.lv>
// SPDX-License-Identifier: AGPL-3.0-or-later
// From https://github.com/WARP-LAB/files_3dmodelviewer

namespace OCA\MusicSheetViewer\Migration;

use OCP\Files\IMimeTypeLoader;
use OCP\Migration\IRepairStep;
use OC\Core\Command\Maintenance\Mimetype\UpdateJS;

abstract class MimeTypeBase implements IRepairStep
{
	const CUSTOM_MIMETYPEMAPPING = 'mimetypemapping.json';
	const CUSTOM_MIMETYPEALIASES = 'mimetypealiases.json';
	// https://www.iana.org/assignments/media-types/media-types.xhtml
	// https://technical.buildingsmart.org/standards/ifc/ifc-formats/
	const EXT_MIME_MAP = array(
		'musicxml' => ['application/vnd.recordare.musicxml+xml'],
		'mxml' => ['application/vnd.recordare.musicxml+xml'],
		'mxl' => ['application/vnd.recordare.musicxml'], // Compressed MusicXML
		'mei' => ['application/mei'], // Music Encoding Initiative

		'gp' => ['application/guitarpro'],      // Guitar Pro
		'gpx' => ['application/guitarpro+gpx'], // Guitar Pro 6
		'gp5' => ['application/guitarpro+gp5'], // Guitar Pro 5
		'gp4' => ['application/guitarpro+gp4'], // Guitar Pro 4
		'gp3' => ['application/guitarpro+gp3'], // Guitar Pro 3

		'ptb' => ['application/powertab'], // Power Tab Editor

		'cap' => ['application/capella'], // Capella
		'capx' => ['application/capella+capx'], // Capella

		'bww' => ['application/bagpipe'], // Bagpipe Music Writer

		'mgu' => ['application/bb+mgu'], // Band-in-a-Box
		'sgu' => ['application/bb+sgu'], // Band-in-a-Box

		'ove' => ['application/overture'], // Overture
		'scw' => ['application/overture'], // Overture

		'midi' => ['audio/midi'],
		'mid' => ['audio/midi'],
		'kar' => ['audio/midi'],

		'mscz' => ['application/musescore'],
	);

	// Preferred extension name, ensures filtering to the extension name passed to score-display
	const CANONIC_EXTENSION = array(
		'application/vnd.recordare.musicxml+xml' => 'musicxml',
		'application/vnd.recordare.musicxml' => 'mxl',
		'application/mei' => 'mei',
		'application/guitarpro' => 'gp',
		'application/guitarpro+gpx' => 'gpx',
		'application/guitarpro+gp5' => 'gp5',
		'application/guitarpro+gp4' => 'gp4',
		'application/guitarpro+gp3' => 'gp3',
		'application/powertab' => 'ptb',
		'application/capella' => 'cap',
		'application/capella+capx' => 'capx',
		'application/bagpipe' => 'bww',
		'application/bb+mgu' => 'mgu',
		'application/bb+sgu' => 'sgu',
		'application/overture' => 'ove',
		'audio/midi' => 'midi',
		'application/musescore' => 'mscz',
	);


	protected $mimeTypeLoader;
	protected $updateJS;

	public function __construct(IMimeTypeLoader $mimeTypeLoader, UpdateJS $updateJS)
	{
		$this->mimeTypeLoader = $mimeTypeLoader;
		$this->updateJS = $updateJS;
	}

	public static function getCanonicExt(string $ext): ?string {
		$ext = strtolower($ext);

		if (!isset(self::EXT_MIME_MAP[$ext])) {
			return null;
		}

		$mime = self::EXT_MIME_MAP[$ext][0];

		return self::CANONIC_EXTENSION[$mime] ?? null;	
	}

	protected function appendToFileMapping(string $filename, array $data) {
		$obj = [];
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
			$obj = json_decode($content, true);
			if (JSON_ERROR_NONE !== json_last_error()) {
				$obj = [];
			}
		}
		foreach ($data as $ext => $mimes) {
			$obj[$ext] = $mimes;
		}
		$mask = empty($obj) ? JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;
		file_put_contents($filename, json_encode($obj, $mask));
	}

	protected function removeFromFileMapping(string $filename, array $data) {
		$obj = [];
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
			$obj = json_decode($content, true);
			if (JSON_ERROR_NONE !== json_last_error()) {
				$obj = [];
			}
		}
		foreach ($data as $ext => $mimes) {
			unset($obj[$ext]);
		}
		$mask = empty($obj) ? JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;
		file_put_contents($filename, json_encode($obj, $mask));
	}

	protected function appendToFileAliases(string $filename, array $data) {
		$obj = [];
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
			$obj = json_decode($content, true);
			if (JSON_ERROR_NONE !== json_last_error()) {
				$obj = [];
			}
		}
		foreach ($data as $ext => $mimes) {
			foreach ($mimes as $mime) {
				$obj[$mime] = $ext;
			}
		}
		$mask = empty($obj) ? JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;
		file_put_contents($filename, json_encode($obj, $mask));
	}

	protected function removeFromFileAliases(string $filename, array $data) {
		$obj = [];
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
			$obj = json_decode($content, true);
			if (JSON_ERROR_NONE !== json_last_error()) {
				$obj = [];
			}
		}
		foreach ($data as $ext => $mimes) {
			foreach ($mimes as $mime) {
				unset($obj[$mime]);
			}
		}
		$mask = empty($obj) ? JSON_FORCE_OBJECT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES : JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES;
		file_put_contents($filename, json_encode($obj, $mask));
	}
}
