import { onBeforeUnmount, unref, watch, type Ref } from 'vue';
import { useEditor } from '@tiptap/vue-3';
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
import { TextStyle } from '@tiptap/extension-text-style';
import { DOMSerializer } from '@tiptap/pm/model';

type UploadImageFn = (file: File) => string | Promise<string>;

type Options = {
    model: Ref<string>;
    filesMap: Ref<Record<string, File>>;
    placeholder: Ref<string>;
    disabled: Ref<boolean>;
    mentionItems: Ref<any[]>;
    uploadImage: Ref<UploadImageFn | null>;
};

const buildSuggestionRenderer = (mentionItems: Ref<any[]>) =>
    suggestion({
        items: ({ query }: { query: string }) => {
            const items = unref(mentionItems) || [];
            if (!query) {
                return items.slice(0, 5);
            }
            return items
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
                    if (props.clientRect) {
                        position(container, props.clientRect());
                    }
                    updateList(props);
                },
                onUpdate: (props: any) => {
                    if (!container) {
                        return;
                    }
                    if (props.clientRect) {
                        position(container, props.clientRect());
                    }
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
                        'w-full rounded-md px-2 py-1 text-left hover:bg-secondary-active';
                    row.textContent = item.label || item.id;
                    row.onclick = () => props.command(item);
                    list?.appendChild(row);
                });
                if (!props.items.length) {
                    const empty = document.createElement('div');
                    empty.className = 'px-2 py-1 text-foreground-faint';
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
    } as any) as any;

const ImageExtension = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            width: {
                default: null,
                parseHTML: (element) => element.getAttribute('data-width'),
                renderHTML: (attributes) => {
                    if (!attributes.width) {
                        return {};
                    }
                    return {
                        'data-width': attributes.width,
                        style: `width: ${attributes.width}; max-width: 100%; height: auto;`,
                    };
                },
            },
            uploadId: {
                default: null,
                parseHTML: (element) => element.getAttribute('data-upload-id'),
                renderHTML: (attributes) => {
                    if (!attributes.uploadId) {
                        return {};
                    }
                    return { 'data-upload-id': attributes.uploadId };
                },
            },
        };
    },
});

const TextStyleExtension = TextStyle.extend({
    addAttributes() {
        return {
            fontSize: {
                default: null,
                parseHTML: (element) => element.style.fontSize || null,
                renderHTML: (attributes) => {
                    if (!attributes.fontSize) {
                        return {};
                    }
                    return { style: `font-size: ${attributes.fontSize};` };
                },
            },
        };
    },
});

export const useRichTextEditor = (options: Options) => {
    const suggestionRenderer = buildSuggestionRenderer(options.mentionItems);
    const editor = useEditor({
        content: options.model.value,
        editable: !options.disabled.value,
        extensions: [
            StarterKit.configure({
                bulletList: { keepMarks: true, keepAttributes: false },
                orderedList: { keepMarks: true, keepAttributes: false },
                link: false,
                underline: false,
            }),
            TextStyleExtension,
            Underline,
            Link.configure({ openOnClick: false }),
            Placeholder.configure({ placeholder: options.placeholder.value }),
            TextAlign.configure({ types: ['heading', 'paragraph'] }),
            ImageExtension,
            Table.configure({ resizable: true }),
            TableRow,
            TableHeader,
            TableCell,
            Mention.configure({
                HTMLAttributes: {
                    class: 'rounded bg-secondary-active px-1 text-foreground',
                },
                suggestion: suggestionRenderer as any,
            }),
        ],
        onUpdate: ({ editor }) => {
            options.model.value = editor.getHTML();
        },
    });

    watch(
        () => options.disabled.value,
        (value) => {
            if (editor.value) {
                editor.value.setEditable(!value);
            }
        },
    );

    watch(
        () => options.model.value,
        (value) => {
            if (!editor.value || editor.value.isDestroyed) {
                return;
            }
            if (editor.value.getHTML() !== value) {
                editor.value.commands.setContent(value || '', {
                    emitUpdate: false,
                });
            }
        },
    );

    onBeforeUnmount(() => {
        editor.value?.destroy();
    });

    const setLink = (href: string) => {
        editor.value?.chain().focus().setLink({ href }).run();
    };

    const unsetLink = () => {
        editor.value?.chain().focus().unsetLink().run();
    };

    const insertImageUrl = (url: string) => {
        editor.value
            ?.chain()
            .focus()
            .setImage({ src: url, width: '50%' } as any)
            .run();
    };

    const handleImageFile = async (event: Event) => {
        const input = event.target as HTMLInputElement;
        const file = input.files?.[0];
        if (!file || !editor.value) {
            return;
        }
        const uploadId = `img_${Math.random().toString(36).slice(2, 8)}`;
        options.filesMap.value = {
            ...(options.filesMap.value || {}),
            [uploadId]: file,
        };
        const uploadImage = unref(options.uploadImage);
        if (typeof uploadImage === 'function') {
            const result = uploadImage(file);
            const url = result instanceof Promise ? await result : result;
            if (url) {
                editor.value
                    ?.chain()
                    .focus()
                    .setImage({
                        src: url,
                        width: '50%',
                        uploadId,
                    } as any)
                    .run();
            }
            return;
        }
        const objectUrl = URL.createObjectURL(file);
        editor.value
            ?.chain()
            .focus()
            .setImage({
                src: objectUrl,
                width: '50%',
                uploadId,
            } as any)
            .run();
    };

    const setImageWidth = (width: string | null) => {
        if (!editor.value) {
            return;
        }
        const attrs = editor.value.getAttributes('image');
        if (!attrs?.src) {
            return;
        }
        editor.value
            .chain()
            .focus()
            .setImage({
                src: attrs.src,
                width,
                uploadId: attrs.uploadId || null,
            } as any)
            .run();
    };

    const removeImage = () => {
        editor.value?.chain().focus().deleteSelection().run();
    };

    const setFontSize = (size: string) => {
        if (!editor.value) {
            return;
        }
        if (size === '16px') {
            editor.value.chain().focus().unsetMark('textStyle').run();
            return;
        }
        editor.value
            .chain()
            .focus()
            .setMark('textStyle', { fontSize: size })
            .run();
    };

    const getSelectedTableHtml = () => {
        if (!editor.value) {
            return null;
        }
        const { state } = editor.value;
        const { $from } = state.selection;
        for (let depth = $from.depth; depth > 0; depth -= 1) {
            const node = $from.node(depth);
            if (node.type.name === 'table') {
                const serializer = DOMSerializer.fromSchema(state.schema);
                const dom = serializer.serializeNode(node);
                const wrapper = document.createElement('div');
                wrapper.appendChild(dom);
                return wrapper.innerHTML;
            }
        }
        return null;
    };

    const copyTable = async () => {
        const html = getSelectedTableHtml();
        if (!html) {
            return;
        }
        await navigator.clipboard.writeText(html);
    };

    return {
        editor,
        setLink,
        unsetLink,
        insertImageUrl,
        handleImageFile,
        setImageWidth,
        removeImage,
        setFontSize,
        copyTable,
    };
};

