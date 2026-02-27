APP_NAME = musicsheetviewer

# export BUILD_MODE=dev to build dev js
BUILD_MODE ?= build

all: build

## Install to APPS-EXTRA in nextcloud-docker-dev container
APPS_EXTRA = /home/augustin64/Downloads/git-repos/nextcloud-docker-dev/workspace/server/apps-extra
install:: build
	rsync -rq . $(APPS_EXTRA)/$(APP_NAME)

$(APP_NAME).zip:: build
	zip -urq $(APP_NAME).zip appinfo css js lib LICENSE templates

## Build and install dependencies
build:: js css js/webmscore/webmscore.lib.data.wasm

# This is a weird hack but .data files are not served statically from apps files in NC
# Thus we rename webmscore.lib.data to webmscore.lib.data.wasm
js/webmscore/webmscore.lib.data.wasm: js/webmscore/webmscore.lib.data js
	mv $< $@
	cd js/webmscore && find . -type f -exec sed -i 's/webmscore\.lib\.data/webmscore.lib.data.wasm/g' {} \;

js: npm-build src/score-display/target
	cp -r src/score-display/target/* js/

css: npm-build
	mv js/score-display.css css/
	cat src/score-display.override.css >> css/score-display.css

npm-build:
	npm run $(BUILD_MODE)

src/score-display/target: src/score-display src/webmscore
	mkdir -p src/score-display/webmscore
	cp -r src/webmscore/* src/score-display/webmscore/
	cd src/score-display && make no-cdn

src/score-display:
	git clone https://github.com/partitioncloud/musescore-web-display src/score-display
