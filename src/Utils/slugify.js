/**
 * Sanitize a string with dashes
 *
 * @returns string Sanitized string
 */
export function slugify(text)
{
    return text
    .toLowerCase()                     // Convert to lowercase
    .trim()                            // Remove leading/trailing whitespace
    .replace(/[^a-z0-9 -]/g, "")       // Remove all non-alphanumeric chars except space and dash
    .replace(/\s+/g, "-")              // Replace spaces with a single dash
    .replace(/-+/g, "-"); 
}
