<template>
  <iframe
    style="width: 100%; height: 100%"
    v-if="isDownloadable"
    ref="iframe"
    :src="iframeSrc" />
  <div v-else id="emptycontent">
    <div class="icon-error" />
      <h3>To view a file, you need a read access to it</h3>
    </div>
</template>

<script>
import { generateUrl } from '@nextcloud/router'

export default {
	props: {
		path: { type: String, required: true },
    mime: { type: String, required: true }
	},
  mounted () {
    window.addEventListener("message", (evt) => {
      if (evt.data == "scoreDisplay:ready") {
        this.doneLoading()
      }
    })
    this.updateHeightWidth()
    this.$nextTick(() => {
      this.$el.focus();
    });
  },
	computed: {
		iframeSrc() {
			return generateUrl('/apps/musicsheetviewer/?file={file}&mime={mime}', {
				file: this.source ?? this.davPath,
        mime: this.mime
			})
		},
		file() {
			// fileList and fileid are provided by the Mime mixin of the Viewer.
      return this.fileList.find((file) => file.fileid === this.fileid)
		},
		isDownloadable() {
			if (!this.file.shareAttributes) {
				return true
			}

			const shareAttributes = JSON.parse(this.file.shareAttributes)
			const downloadPermissions = shareAttributes.find(({ scope, key }) => scope === 'permissions' && key === 'download')
			if (downloadPermissions) {
				return downloadPermissions.value
			}

			return true
		},
  }
}
</script>
