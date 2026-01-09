<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import Image from '@tiptap/extension-image';
import { Table } from '@tiptap/extension-table';
import TableCell from '@tiptap/extension-table-cell';
import TableRow from '@tiptap/extension-table-row';
import TableHeader from '@tiptap/extension-table-header';
import Mention from '@tiptap/extension-mention';
import suggestion from '@tiptap/suggestion';
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
});

const model = defineModel({
    type: String,
    default: '',
});

const suggestionRenderer = suggestion({
    items: ({ query }: { query: string }) => {
        if (!query) {
            return props.mentionItems.slice(0, 5);
        }
        return props.mentionItems
            .filter((item: any) =>
                String(item?.label ?? '')
                    .toLowerCase()
                    .includes(query.toLowerCase()),
            )
            .slice(0, 5);
    },
    render: () => {
        let container: HTMLDivElement | null = null;
        let list: HTMLDivElement | null = null;

        return {
            onStart: (props: any) => {
                container = document.createElement('div');
                container.className =
                    'z-50 rounded-lg border border-border bg-card p-2 text-sm text-card-foreground shadow-lg';
                list = document.createElement('div');
                list.className = 'grid gap-1';
                container.appendChild(list);
                document.body.appendChild(container);
                props.clientRect && position(container, props.clientRect());
                updateList(props);
            },
            onUpdate: (props: any) => {
                if (!container) {
                    return;
                }
                props.clientRect && position(container, props.clientRect());
                updateList(props);
            },
            onKeyDown: (props: any) => {
                if (props.event.key === 'Escape') {
                    props.command({ id: null });
                    return true;
                }
                return false;
            },
            onExit: () => {
                if (container) {
                    container.remove();
                }
            },
        };

        function updateList(props: any) {
            if (!list) {
                return;
            }
            list.innerHTML = '';
            props.items.forEach((item: any) => {
                const row = document.createElement('button');
                row.type = 'button';
                row.className =
                    'w-full rounded-md px-2 py-1 text-left hover:bg-secondary/60';
                row.textContent = item.label || item.id;
                row.onclick = () => props.command(item);
                list?.appendChild(row);
            });
            if (!props.items.length) {
                const empty = document.createElement('div');
                empty.className = 'px-2 py-1 text-foreground/60';
                empty.textContent = 'No matches';
                list.appendChild(empty);
            }
        }

        function position(el: HTMLElement, rect: DOMRect) {
            el.style.position = 'absolute';
            el.style.left = `${rect.left + window.scrollX}px`;
            el.style.top = `${rect.bottom + window.scrollY + 6}px`;
        }
    },
});

const editor = useEditor({
    content: model.value,
    editable: !props.disabled,
    extensions: [
        StarterKit.configure({
            bulletList: { keepMarks: true, keepAttributes: false },
            orderedList: { keepMarks: true, keepAttributes: false },
        }),
        Underline,
        Link.configure({ openOnClick: false }),
        Placeholder.configure({ placeholder: props.placeholder }),
        TextAlign.configure({ types: ['heading', 'paragraph'] }),
        Image,
        Table.configure({ resizable: true }),
        TableRow,
        TableHeader,
        TableCell,
        Mention.configure({
            HTMLAttributes: {
                class: 'rounded bg-secondary/60 px-1 text-foreground',
            },
            suggestion: suggestionRenderer,
        }),
    ],
    onUpdate: ({ editor }) => {
        model.value = editor.getHTML();
    },
});

watch(
    () => props.disabled,
    (value) => {
        if (editor.value) {
            editor.value.setEditable(!value);
        }
    },
);

watch(
    () => model.value,
    (value) => {
        if (!editor.value || editor.value.isDestroyed) {
            return;
        }
        if (editor.value.getHTML() !== value) {
            editor.value.commands.setContent(value || '', false);
        }
    },
);

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const buttonClass = (active = false) =>
    cn(
        'inline-flex h-9 w-9 items-center justify-center rounded-md border border-transparent text-foreground/70 transition hover:bg-secondary/60 hover:text-foreground',
        active ? 'bg-secondary/70 text-foreground' : '',
    );

const actionButtonClass = 'inline-flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background text-foreground/70 hover:bg-secondary/60';

const promptForLink = () => {
    if (!editor.value) {
        return;
    }
    const previousUrl = editor.value.getAttributes('link').href;
    const url = window.prompt('URL', previousUrl || '');
    if (url === null) {
        return;
    }
    if (url === '') {
        editor.value.chain().focus().unsetLink().run();
        return;
    }
    editor.value.chain().focus().setLink({ href: url }).run();
};

const promptForImage = () => {
    if (!editor.value) {
        return;
    }
    const url = window.prompt('Image URL');
    if (!url) {
        return;
    }
    editor.value.chain().focus().setImage({ src: url }).run();
};
</script>

<template>
    <div class="rounded-lg border border-border bg-card text-card-foreground">
        <div class="flex flex-wrap items-center gap-1 border-b border-border bg-secondary/30 p-2">
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
        </div>
        <EditorContent
            :editor="editor"
            class="prose prose-sm max-w-none p-4 text-foreground"
        />
    </div>
</template>

<style scoped>
:deep(.ProseMirror) {
    min-height: 180px;
    outline: none;
}
</style>
