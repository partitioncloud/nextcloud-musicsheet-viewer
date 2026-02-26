<?php
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2012-2016 ownCloud, Inc.
 * SPDX-FileCopyrightText: 2012 Mozilla Foundation
 * SPDX-FileCopyrightText: 1990-2015 Adobe Systems Incorporated.
 * SPDX-License-Identifier: Apache-2.0
 */
/** @var array $_ */
/** @var OCP\IURLGenerator $urlGenerator */
$urlGenerator = $_['urlGenerator'];
$version = \OC::$server->getAppManager()->getAppVersion('musicsheetviewer');

use OCA\MusicSheetViewer\Migration\MimeTypeBase;

$file = $_GET['file'] ?? '';
$ext = pathinfo($file, PATHINFO_EXTENSION);
$type = MimeTypeBase::getCanonicExt($ext);
?>

<!DOCTYPE html>
<!--
Copyright 2012 Mozilla Foundation

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Adobe CMap resources are covered by their own copyright but the same license:

    Copyright 1990-2015 Adobe Systems Incorporated.

See https://github.com/adobe-type-tools/cmap-resources
-->
<html dir="ltr" mozdisallowselectionprint>
  <link rel="stylesheet" href="<?php p($urlGenerator->linkTo('musicsheetviewer', 'css/score-display.css')) ?>" />
  <script type="module"
    src="<?php p($urlGenerator->linkTo('musicsheetviewer', 'js/score-display.global.js')) ?>"
    nonce="<?php p(\OC::$server->getContentSecurityPolicyNonceManager()->getNonce()) ?>"
></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="google" content="notranslate">
  <body>
  <score-display src="<?php p($file)?>" type="<?php p($type ?? '')?>">
      <score-track src="<?php p($file)?>" type="mscz/synth:all">Tutti</score-track>
      <score-download href="<?php p($file)?>">Download</score-download>
    </score-display>
  </body>
</html>
