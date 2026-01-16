<script setup lang="ts">
import { computed, ref, toRef } from 'vue';
import { EditorContent } from '@tiptap/vue-3';
import { cn } from '@/lib/utils';
import Button from '@/components/ui/Button.vue';
import Dialog from '@/components/ui/Dialog.vue';
import Input from '@/components/ui/Input.vue';
import Label from '@/components/ui/Label.vue';
import Icon from '@/components/Icon.vue';
import PopoverBase from '@/components/ui/PopoverBase.vue';
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
    disabledActions: {
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
const fontSizeOpen = ref(false);
const fontSizeOptions = ['14px', '16px', '18px', '22px', '28px'];

const actionGroups: Record<string, string[]> = {
    headers: ['heading1', 'heading2', 'heading3'],
    lists: ['bulletList', 'orderedList'],
    align: ['alignLeft', 'alignCenter', 'alignRight'],
};

const disabledActionSet = computed(
    () => new Set((props.disabledActions as string[]).map((item) => String(item))),
);

const isActionHidden = (action: string) => {
    if (disabledActionSet.value.has(action)) {
        return true;
    }
    return Object.entries(actionGroups).some(
        ([group, actions]) =>
            disabledActionSet.value.has(group) && actions.includes(action),
    );
};

const visible = computed(() => ({
    inline: ['bold', 'italic', 'underline', 'strike'].some((id) => !isActionHidden(id)),
    headers: ['heading1', 'heading2', 'heading3'].some((id) => !isActionHidden(id)),
    fontSize: !isActionHidden('fontSize'),
    lists: ['bulletList', 'orderedList', 'blockquote', 'codeBlock'].some(
        (id) => !isActionHidden(id),
    ),
    align: ['alignLeft', 'alignCenter', 'alignRight'].some((id) => !isActionHidden(id)),
    insert: ['link', 'image', 'table'].some((id) => !isActionHidden(id)),
    history: ['undo', 'redo'].some((id) => !isActionHidden(id)),
}));

const showSeparator = (left: boolean, right: boolean) => left && right;

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

const buttonClass = (action: string, active = false) =>
    cn(
        'inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent text-foreground-subtle transition hover:bg-secondary-active hover:text-foreground',
        active ? 'bg-secondary-strong text-foreground' : '',
    );

const actionButtonClass = (action: string) =>
    cn(
        'inline-flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background text-foreground-subtle hover:bg-secondary-active',
    );

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
    fontSizeOpen.value = false;
};
</script>

<template>
    <div class="rounded-lg border border-border bg-card text-card-foreground">
        <div class="flex flex-wrap items-center gap-1 border-b border-border bg-secondary-muted p-2">
            <button
                v-if="!isActionHidden('bold')"
                :class="buttonClass('bold', editor?.isActive('bold'))"
                type="button"
                @click="editor?.chain().focus().toggleBold().run()"
            >
                <IconLucideBold class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('italic')"
                :class="buttonClass('italic', editor?.isActive('italic'))"
                type="button"
                @click="editor?.chain().focus().toggleItalic().run()"
            >
                <IconLucideItalic class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('underline')"
                :class="buttonClass('underline', editor?.isActive('underline'))"
                type="button"
                @click="editor?.chain().focus().toggleUnderline().run()"
            >
                <IconLucideUnderline class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('strike')"
                :class="buttonClass('strike', editor?.isActive('strike'))"
                type="button"
                @click="editor?.chain().focus().toggleStrike().run()"
            >
                <IconLucideStrikethrough class="h-4 w-4" />
            </button>
            <span
                v-if="showSeparator(visible.inline, visible.headers || visible.fontSize)"
                class="mx-1 h-5 w-px bg-border"
            />
            <button
                v-if="!isActionHidden('heading1')"
                :class="buttonClass('heading1', editor?.isActive('heading', { level: 1 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()"
            >
                <Icon name="lucide:heading-1" class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('heading2')"
                :class="buttonClass('heading2', editor?.isActive('heading', { level: 2 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
            >
                <Icon name="lucide:heading-2" class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('heading3')"
                :class="buttonClass('heading3', editor?.isActive('heading', { level: 3 }))"
                type="button"
                @click="editor?.chain().focus().toggleHeading({ level: 3 }).run()"
            >
                <Icon name="lucide:heading-3" class="h-4 w-4" />
            </button>
            <PopoverBase
                v-if="!isActionHidden('fontSize')"
                v-model:open="fontSizeOpen"
                content-class="w-[3.75rem] min-w-[3.75rem] p-1"
                :match-trigger-width="true"
                trigger-width-var="--rte-font-size-width"
                :side-offset="6"
            >
                <template #trigger>
                    <button
                        type="button"
                        class="ml-1 inline-flex h-9 w-[3.75rem] items-center justify-between gap-1 rounded-md px-1 text-sm text-foreground transition hover:bg-secondary-active"
                        style="--rte-font-size-width: 3.75rem;"
                    >
                        <span>{{ fontSize }}</span>
                        <IconLucideChevronDown class="h-4 w-4 text-foreground-faint" />
                    </button>
                </template>
                <div class="grid gap-1">
                    <button
                        v-for="size in fontSizeOptions"
                        :key="size"
                        type="button"
                        class="w-full rounded-md px-2 py-1 text-left text-sm text-foreground hover:bg-secondary-hover"
                        @click="setFontSize(size)"
                    >
                        {{ size }}
                    </button>
                </div>
            </PopoverBase>
            <span
                v-if="showSeparator(visible.headers || visible.fontSize, visible.lists)"
                class="mx-1 h-5 w-px bg-border"
            />
            <button
                v-if="!isActionHidden('bulletList')"
                :class="buttonClass('bulletList', editor?.isActive('bulletList'))"
                type="button"
                @click="editor?.chain().focus().toggleBulletList().run()"
            >
                <IconLucideList class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('orderedList')"
                :class="buttonClass('orderedList', editor?.isActive('orderedList'))"
                type="button"
                @click="editor?.chain().focus().toggleOrderedList().run()"
            >
                <IconLucideListOrdered class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('blockquote')"
                :class="buttonClass('blockquote', editor?.isActive('blockquote'))"
                type="button"
                @click="editor?.chain().focus().toggleBlockquote().run()"
            >
                <IconLucideQuote class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('codeBlock')"
                :class="buttonClass('codeBlock', editor?.isActive('codeBlock'))"
                type="button"
                @click="editor?.chain().focus().toggleCodeBlock().run()"
            >
                <IconLucideCode class="h-4 w-4" />
            </button>
            <span
                v-if="showSeparator(visible.lists, visible.align)"
                class="mx-1 h-5 w-px bg-border"
            />
            <button
                v-if="!isActionHidden('alignLeft')"
                :class="buttonClass('alignLeft', editor?.isActive({ textAlign: 'left' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('left').run()"
            >
                <IconLucideAlignLeft class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('alignCenter')"
                :class="buttonClass('alignCenter', editor?.isActive({ textAlign: 'center' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('center').run()"
            >
                <IconLucideAlignCenter class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('alignRight')"
                :class="buttonClass('alignRight', editor?.isActive({ textAlign: 'right' }))"
                type="button"
                @click="editor?.chain().focus().setTextAlign('right').run()"
            >
                <IconLucideAlignRight class="h-4 w-4" />
            </button>
            <span
                v-if="showSeparator(visible.align, visible.insert)"
                class="mx-1 h-5 w-px bg-border"
            />
            <button
                v-if="!isActionHidden('link')"
                :class="buttonClass('link', editor?.isActive('link'))"
                type="button"
                @click="promptForLink"
            >
                <Icon name="lucide:link-2" class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('image')"
                :class="actionButtonClass('image')"
                type="button"
                @click="promptForImage"
            >
                <IconLucideImage class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('table')"
                :class="actionButtonClass('table')"
                type="button"
                @click="editor?.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()"
            >
                <Icon name="lucide:table-2" class="h-4 w-4" />
            </button>
            <span
                v-if="showSeparator(visible.insert, visible.history)"
                class="mx-1 h-5 w-px bg-border"
            />
            <button
                v-if="!isActionHidden('undo')"
                :class="actionButtonClass('undo')"
                type="button"
                @click="editor?.chain().focus().undo().run()"
            >
                <IconLucideUndo class="h-4 w-4" />
            </button>
            <button
                v-if="!isActionHidden('redo')"
                :class="actionButtonClass('redo')"
                type="button"
                @click="editor?.chain().focus().redo().run()"
            >
                <IconLucideRedo class="h-4 w-4" />
            </button>
            <template v-if="editor?.isActive('image')">
                <span class="mx-1 h-5 w-px bg-border" />
            </template>
        </div>
        <div
            v-if="editor?.isActive('table') && !isActionHidden('table')"
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
            v-else-if="editor?.isActive('image') && !isActionHidden('image')"
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
