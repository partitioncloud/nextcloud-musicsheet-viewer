
all: build

## Install to APPS-EXTRA in nextcloud-docker-dev container
APPS_EXTRA = /home/augustin64/Downloads/git-repos/nextcloud-docker-dev/workspace/server/apps-extra
install:: build
	rsync -rq . $(APPS_EXTRA)/musicsheetviewer

install-dev:: build-dev
	rsync -rq . $(APPS_EXTRA)/musicsheetviewer

## Build and install dependencies
build:: src/score-display/target
	npm run build
	cp -r src/score-display/target/* js/
	mv js/score-display.css css/
	cat src/score-display.override.css >> css/score-display.css

build-dev:: src/score-display/target
	npm run dev
	cp -r src/score-display/target/* js/
	mv js/score-display.css css/
	cat src/score-display.override.css >> css/score-display.css

src/score-display/target: src/score-display src/webmscore
	cp -r src/webmscore src/score-display/webmscore
	cd src/score-display && make no-cdn

src/score-display:
	git clone https://github.com/partitioncloud/musescore-web-display src/score-display
