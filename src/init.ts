import { registerHandler } from '@nextcloud/viewer'
import SheetView from './SheetView.vue'

registerHandler({
	id: 'musicsheetviewer',
	mimes: [
		'application/vnd.recordare.musicxml+xml',
		'application/musescore',
		'audio/midi',
	],
	component: SheetView,
});
