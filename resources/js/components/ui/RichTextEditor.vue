<script setup lang="ts">
import { computed, ref, toRef } from 'vue';
import { EditorContent } from '@tiptap/vue-3';
import {
    Bold,
    Italic,
    Underline as UnderlineIcon,
    Strikethrough,
    List,
    ListOrdered,
    Quote,
    Code,
    Heading1,
    Heading2,
    Heading3,
    Undo,
    Redo,
    Link2,
    Image as ImageIcon,
    AlignLeft,
    AlignCenter,
    AlignRight,
    Table2,
} from 'lucide-vue-next';
import { cn } from '@/lib/utils';
import Button from '@/components/ui/Button.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import { useRichTextEditor } from '@/composables/useRichTextEditor';
import FieldError from '@/components/ui/FieldError.vue';

const props = defineProps({
    placeholder: {
        type: String,
        default: 'Start writing...',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    mentionItems: {
        type: Array,
        default: () => [],
    },
    uploadImage: {
        type: Function,
        default: null,
    },
    error: {
        type: [String, Array, Object],
        default: '',
    },
});

const model = defineModel({
    type: String,
    default: '',
});

const filesMap = defineModel('files', {
    type: Object,
    default: () => ({}),
});

/*
Submission strategy (HTML + files map)
1) Bind editor HTML and files map from parent:
  <RichTextEditor v-model="html" v-model:files="filesMap" />
2) On image upload (local file), the editor inserts:
  - src: object URL (preview only)
  - data-upload-id: short id (used as filename key)
3) The files map is { [uploadId]: File }.
4) Submit with FormData (multipart):
  - html (string)
  - file_ids[] (uploadId list)
  - files[] (File list) in the same order as file_ids[]
  Use buildRichTextFormData(html, filesMap) from:
  - resources/js/lib/richText.ts
5) In controller:
  - Loop over file_ids/files, store each file, build final URL
  - Replace each <img data-upload-id="..."> src with stored URL
  - save HTML
*/


const isLinkDialogOpen = ref(false);
const linkUrl = ref('');
const isImageDialogOpen = ref(false);
const imageUrl = ref('');
const imageFileInput = ref<HTMLInputElement | null>(null);
const fontSize = ref('16px');
const hasLink = computed(() => Boolean(editor.value?.isActive('link')));

const {
    editor,
    setLink,
    unsetLink,
    insertImageUrl,
    handleImageFile,
    setImageWidth,
    removeImage,
    setFontSize: applyFontSize,
    copyTable,
} = useRichTextEditor({
    model,
    filesMap,
    placeholder: toRef(props, 'placeholder'),
    disabled: toRef(props, 'disabled'),
    mentionItems: toRef(props, 'mentionItems'),
    uploadImage: toRef(props, 'uploadImage'),
});

const buttonClass = (active = false) =>
    cn(
        'inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground',
        active ? 'bg-secondary-strong text-foreground' : '',
    );

const actionButtonClass = 'inline-flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background text-foreground-subtle hover:bg-secondary-active';

const promptForLink = () => {
    if (!editor.value) {
        return;
    }
    linkUrl.value = editor.value.getAttributes('link').href || '';
    isLinkDialogOpen.value = true;
};

const promptForImage = () => {
    if (!editor.value) {
        return;
    }
    imageUrl.value = '';
    isImageDialogOpen.value = true;
};

const applyLink = () => {
    if (!editor.value) {
        return;
    }
    if (!linkUrl.value) {
        unsetLink();
        isLinkDialogOpen.value = false;
        return;
    }
    setLink(linkUrl.value);
    isLinkDialogOpen.value = false;
};

const removeLink = () => {
    unsetLink();
    isLinkDialogOpen.value = false;
};

const applyImage = () => {
    if (!editor.value || !imageUrl.value) {
        return;
    }
    insertImageUrl(imageUrl.value);
    isImageDialogOpen.value = false;
};

const onImageFileChange = async (event: Event) => {
    await handleImageFile(event);
    isImageDialogOpen.value = false;
    imageUrl.value = '';
    if (imageFileInput.value) {
        imageFileInput.value.value = '';
    }
};

const setFontSize = (size: string) => {
    fontSize.value = size;
    applyFontSize(size);
};
</script>

<template>
    <div class="rounded-lg border border-border bg-card text-card-foreground">
        <div class="flex flex-wrap items-center gap-1 border-b border-border bg-secondary-muted p-2">
            <button
                :class="buttonClass(editor?.isActive('bold'))"
                type="button"
                @click="editor?.chain().focus().toggleBold().run()"
            >
                <Bold class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('italic'))"
                type="button"
                @click="editor?.chain().focus().toggleItalic().run()"
            >
                <Italic class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('underline'))"
                type="button"
                @click="editor?.chain().focus().toggleUnderline().run()"
            >
                <UnderlineIcon class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('strike'))"
                type="button"
                @click="editor?.chain().focus().toggleStrike().run()"
            >
                <Strikethrough class="h-4 w-4" />
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                :class="buttonClass(editor?.isActive('heading', { level: 1 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()"
            >
                <Heading1 class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('heading', { level: 2 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
            >
                <Heading2 class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('heading', { level: 3 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()"
            >
                <Heading3 class="h-4 w-4" />
            </button>
            <select
                class="ml-1 h-9 rounded-md border border-border bg-background px-2 text-sm text-foreground"
                :value="fontSize"
                @change="setFontSize(($event.target as HTMLSelectElement).value)"
            >
                <option value="14px">14px</option>
                <option value="16px">16px</option>
                <option value="18px">18px</option>
                <option value="22px">22px</option>
                <option value="28px">28px</option>
            </select>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                :class="buttonClass(editor?.isActive('bulletList'))"
                type="button"
                @click="editor?.chain().focus().toggleBulletList().run()"
            >
                <List class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('orderedList'))"
                type="button"
                @click="editor?.chain().focus().toggleOrderedList().run()"
            >
                <ListOrdered class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('blockquote'))"
                type="button"
                @click="editor?.chain().focus().toggleBlockquote().run()"
            >
                <Quote class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive('codeBlock'))"
                type="button"
                @click="editor?.chain().focus().toggleCodeBlock().run()"
            >
                <Code class="h-4 w-4" />
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                :class="buttonClass(editor?.isActive({ textAlign: 'left' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('left').run()"
            >
                <AlignLeft class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive({ textAlign: 'center' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('center').run()"
            >
                <AlignCenter class="h-4 w-4" />
            </button>
            <button
                :class="buttonClass(editor?.isActive({ textAlign: 'right' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('right').run()"
            >
                <AlignRight class="h-4 w-4" />
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                :class="buttonClass(editor?.isActive('link'))"
                type="button"
                @click="promptForLink"
            >
                <Link2 class="h-4 w-4" />
            </button>
            <button
                :class="actionButtonClass"
                type="button"
                @click="promptForImage"
            >
                <ImageIcon class="h-4 w-4" />
            </button>
            <button
                :class="actionButtonClass"
                type="button"
                @click="editor?.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()"
            >
                <Table2 class="h-4 w-4" />
            </button>
            <span class="mx-1 h-5 w-px bg-border" />
            <button
                :class="actionButtonClass"
                type="button"
                @click="editor?.chain().focus().undo().run()"
            >
                <Undo class="h-4 w-4" />
            </button>
            <button
                :class="actionButtonClass"
                type="button"
                @click="editor?.chain().focus().redo().run()"
            >
                <Redo class="h-4 w-4" />
            </button>
            <template v-if="editor?.isActive('image')">
                <span class="mx-1 h-5 w-px bg-border" />
            </template>
        </div>
        <div
            v-if="editor?.isActive('table')"
            class="flex items-center gap-2 border-b border-border bg-secondary-soft px-2 py-2 text-xs text-foreground-subtle"
        >
            <span class="text-[11px] font-semibold uppercase tracking-wide text-foreground-disabled">
                Table
            </span>
            <div class="flex flex-wrap items-center gap-1">
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().addColumnBefore().run()"
                >
                    +Col Left
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().addColumnAfter().run()"
                >
                    +Col Right
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().addRowBefore().run()"
                >
                    +Row Above
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().addRowAfter().run()"
                >
                    +Row Below
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().deleteColumn().run()"
                >
                    -Col
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().deleteRow().run()"
                >
                    -Row
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="editor?.chain().focus().deleteTable().run()"
                >
                    Delete
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="copyTable"
                >
                    Copy
                </button>
            </div>
        </div>
        <div
            v-else-if="editor?.isActive('image')"
            class="flex items-center gap-2 border-b border-border bg-secondary-soft px-2 py-2 text-xs text-foreground-subtle"
        >
            <span class="text-[11px] font-semibold uppercase tracking-wide text-foreground-disabled">
                Image
            </span>
            <div class="flex flex-wrap items-center gap-1">
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="setImageWidth('25%')"
                >
                    S
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="setImageWidth('50%')"
                >
                    M
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="setImageWidth('75%')"
                >
                    L
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="setImageWidth('100%')"
                >
                    Full
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="setImageWidth(null)"
                >
                    Auto
                </button>
                <button
                    class="h-7 rounded-md border border-border bg-background px-2 text-xs text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground whitespace-nowrap"
                    type="button"
                    @click="removeImage"
                >
                    Remove
                </button>
            </div>
        </div>
        <EditorContent
            :editor="editor"
            class="prose prose-sm max-w-none p-4 text-foreground"
        />

        <Dialog v-model:open="isLinkDialogOpen">
            <template #title>Insert link</template>
            <template #description>
                Provide a URL to apply to the selected text.
            </template>
            <div class="grid gap-2">
                <Label for="rte-link">URL</Label>
                <Input id="rte-link" v-model="linkUrl" type="url" placeholder="https://..." />
            </div>
            <template #footer="{ close }">
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button v-if="hasLink" variant="outline" @click="removeLink">Remove link</Button>
                <Button @click="applyLink">Apply link</Button>
            </template>
        </Dialog>

        <Dialog v-model:open="isImageDialogOpen">
            <template #title>Insert image</template>
            <template #description>
                Paste an image URL or upload a file from your device.
            </template>
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="rte-image-url">Image URL</Label>
                    <Input id="rte-image-url" v-model="imageUrl" type="url" placeholder="https://..." />
                </div>
                <div class="grid gap-2">
                    <Label for="rte-image-file">Upload image</Label>
                    <input
                        id="rte-image-file"
                        ref="imageFileInput"
                        type="file"
                        accept="image/*"
                        class="text-sm text-foreground-subtle"
                        @change="onImageFileChange"
                    />
                </div>
            </div>
            <template #footer="{ close }">
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button @click="applyImage">Insert image</Button>
            </template>
        </Dialog>
    </div>
    <FieldError class="mt-1" :error="props.error" />
</template>

<style scoped>
:deep(.ProseMirror) {
    min-height: 180px;
    outline: none;
}

:deep(.ProseMirror h1) {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0.5rem 0;
}

:deep(.ProseMirror h2) {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0.5rem 0;
}

:deep(.ProseMirror h3) {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0.5rem 0;
}

:deep(.ProseMirror ul) {
    list-style: disc;
    padding-left: 1.5rem;
}

:deep(.ProseMirror ol) {
    list-style: decimal;
    padding-left: 1.5rem;
}

:deep(.ProseMirror blockquote) {
    border-left: 3px solid var(--border);
    padding-left: 1rem;
    color: color-mix(in oklab, var(--foreground) 70%, transparent);
}

:deep(.ProseMirror a) {
    color: var(--accent);
    text-decoration: underline;
    text-underline-offset: 2px;
}

:deep(.ProseMirror code) {
    background: color-mix(in oklab, var(--secondary) 60%, transparent);
    padding: 0.1rem 0.3rem;
    border-radius: 0.25rem;
}

:deep(.ProseMirror pre) {
    background: var(--secondary);
    color: var(--foreground);
    padding: 0.75rem;
    border-radius: 0.5rem;
    overflow-x: auto;
}

:deep(.ProseMirror table) {
    width: 100%;
    border-collapse: collapse;
    margin: 0.5rem 0;
}

:deep(.ProseMirror th),
:deep(.ProseMirror td) {
    border: 1px solid color-mix(in oklab, var(--border) 60%, var(--foreground) 40%);
    padding: 0.5rem;
    text-align: left;
}

:deep(.ProseMirror img) {
    max-width: 100%;
    border-radius: 0.5rem;
    height: auto;
    display: block;
}
</style>

