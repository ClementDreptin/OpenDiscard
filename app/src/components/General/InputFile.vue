<template>
    <div class="field">
        <div class="file">
            <label class="file-label">
                <input class="file-input" ref="file" @change="encodeImage" type="file" name="resume">
                <span class="file-cta">
                    <span class="file-icon">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="file-label">Choose an image...</span>
                </span>
            </label>
            <span v-show="$parent.fileName" class="file-name">{{ $parent.fileName }}</span>
        </div>
        <div class="note">
            <strong>Note:</strong> It is recommended to choose an image with an aspect ratio of 1 (width = height).
        </div>
    </div>
</template>

<script>
    export default {
        name: "InputFile",
        methods: {
            encodeImage() {
                this.$parent.fail = null;
                let file = this.$refs.file.files[0];
                if (!file.type.match(/image.*/)) return this.$parent.fail = "You must select an image.";
                this.$parent.fileName = file.name;
                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = () => this.$parent.fileData = reader.result.replace(/^data:image\/.*;base64,/, "");
            }
        }
    }
</script>

<style scoped>
    .note {
        margin-top: 0.5em;
        color: #b5b5b5;
        font-size: 0.85em;
    }

    .note strong {
        color: #b5b5b5;
    }

    .file-name {
        margin-left: 0.5em;
        border-width: 1px;
    }
</style>