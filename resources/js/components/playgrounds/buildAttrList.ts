type AttrItem = {
    attr: string;
    when?: boolean;
};

export const buildAttrList = (items: AttrItem[]): string[] =>
    items
        .filter((item) => item.when ?? true)
        .map((item) => item.attr)
        .filter(Boolean);
