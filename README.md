# MusicSheet viewer for Nextcloud

<img width="2256" height="1377" alt="Screenshot" src="https://github.com/user-attachments/assets/140d2ddd-1930-4d70-9b75-c2acdc703178" />

## Visualize and play music sheets

|Extension|Type|Notes|
|--|--|--|
|mscz|Compressed MuseScore native format||
|mscx|MuseScore native format||
|musicxml,mxml|Uncompressed MusicXML||
|mxl|Compressed MusicXML||
|midi,mid,kar|MIDI|Currently unsupported|
|gp,gp3,gp4,gp5,**\***|Guitar Pro||
<sub>**\*** `.gpx` disabled to avoid interferring with GPs eXchange format</sub>

And many more (untested), see [MuseScore handbook for full list](https://musescore.org/en/handbook/3/file-formats#share-with-other-software).

The scores are fully processed in the client browser, thus the viewer is also accessible in public shares, without any security concern.


## Intallation

TBA: Through Nextcloud App Store

### Building from source

```bash
# Clone this repo
git clone https://github.com/partitioncloud/nextcloud-musicsheet-viewer
cd nextcloud-musicsheet-viewer
# Build
make musicsheetviewer.zip
```

### Development notes

This app injects itself in [`@nextcloud/viewer`](https://github.com/nextcloud/viewer) for supported filetypes.
Apart from Nextcloud documentation, these great apps helped a lot in the building of this project:
- <https://github.com/nextcloud/files_pdfviewer>
- <https://github.com/WARP-LAB/files_3dmodelviewer>
- <https://github.com/nextcloud/viewer>

This app is mainly a wrapper around [musescore-web-display](https://github.com/partitioncloud/musescore-web-display), which is itself a fancy wrapper around [WebMscore](https://github.com/LibreScore/webmscore).
