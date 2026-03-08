APP_NAME = musicsheetviewer

# export BUILD_MODE=dev to build dev js
BUILD_MODE ?= build

all: build

## Install to APPS-EXTRA in nextcloud-docker-dev container
APPS_EXTRA ?= ~/nextcloud-docker-dev/workspace/server/apps-extra
install:: build
	rsync -rq --delete . $(APPS_EXTRA)/$(APP_NAME)

$(APP_NAME).tar.gz:: build
	tar -czf $(APP_NAME).tar.gz --transform "s,^,$(APP_NAME)/," appinfo css js img lib LICENSE templates
ifneq (,$(wildcard ~/.nextcloud/certificates/$(APP_NAME).key))
	@echo "Signature:"
	openssl dgst -sha512 -sign ~/.nextcloud/certificates/$(APP_NAME).key $(APP_NAME).tar.gz | openssl base64
endif

## Build and install dependencies
build:: js css img js/webmscore/webmscore.lib.data.wasm js/soundfonts/FluidR3Mono_GM.sf3.ogg

# This is a weird hack but .data files are not served statically from apps files in NC
# Thus we rename webmscore.lib.data to webmscore.lib.data.wasm
js/webmscore/webmscore.lib.data.wasm: js/webmscore/webmscore.lib.data js
	mv $< $@
	cd js/webmscore && find . -type f -exec sed -i 's/webmscore\.lib\.data/webmscore.lib.data.wasm/g' {} \;

# Same hack
js/soundfonts/FluidR3Mono_GM.sf3.ogg: js/soundfonts/FluidR3Mono_GM.sf3
	mv $< $@
	sed -i 's/FluidR3Mono_GM\.sf3/FluidR3Mono_GM.sf3.ogg/g' js/score-display.global.js

js: npm-build src/score-display/target
	cp -r src/score-display/target/* js/

css: npm-build
	mv js/score-display.css css/
	cat src/score-display.override.css >> css/score-display.css

img: $(wildcard src/img/*.svg)
	mkdir -p img
	cp src/img/* img -r

npm-build: node_modules
	npm run $(BUILD_MODE)

node_modules:
	npm ci

src/score-display/target: src/score-display
	cd src/score-display && npm uninstall @magenta/music tone
	cd src/score-display && make no-cdn

clean:
	$(RM) -r js css src/score-display
