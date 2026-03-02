import { registerHandler } from '@nextcloud/viewer'
import SheetView from './SheetView.vue'

registerHandler({
	id: 'musicsheetviewer',
	mimes: [
		'application/vnd.recordare.musicxml+xml',
		'application/vnd.recordare.musicxml',
		'application/mei',
		'application/guitarpro',
		'application/guitarpro+gpx',
		'application/guitarpro+gp5',
		'application/guitarpro+gp4',
		'application/guitarpro+gp3',
		'application/powertab',
		'application/capella',
		'application/capella+capx',
		'application/bagpipe',
		'application/bb+mgu',
		'application/bb+sgu',
		'application/overture',
		'audio/midi',
		'application/musescore',
		'application/musescore+mscx',
	],
	component: SheetView,
});
