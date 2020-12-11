import { mimeFiles } from "../enums/MimeTypesFile.js";

export function checkExtension(file, extensions) {
    const extension = file.name.split('.').pop().toLowerCase();

    return extensions.includes(extension);
}

export function checkMimeType(file) {
    const extension = file.name.split('.').pop().toLowerCase();

    return mimeFiles[extension] ? mimeFiles[extension].includes(file.type) : false;
}
