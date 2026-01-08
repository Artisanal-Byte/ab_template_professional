export const buildRichTextFormData = (
    html: string,
    files: Record<string, File>,
) => {
    const formData = new FormData();
    formData.append('html', html || '');

    Object.entries(files || {}).forEach(([id, file]) => {
        if (!(file instanceof File)) {
            return;
        }
        formData.append('file_ids[]', id);
        formData.append('files[]', file);
    });

    return formData;
};
