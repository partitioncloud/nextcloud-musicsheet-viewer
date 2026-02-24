<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: WARP <development@warp.lv>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\MusicSheetViewer\Listener;
use OCA\MusicSheetViewer\AppInfo\Application;

use OCA\Viewer\Event\LoadViewer;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

class LoadViewerListener implements IEventListener {
	public function handle(Event $event): void {
		if (!$event instanceof LoadViewer) {
			return;
		}
		Util::addInitScript(Application::APP_ID(), 'musicsheetviewer', 'viewer');
		Util::addInitScript(Application::APP_ID(), 'musicsheetviewer-init');
	}
}
