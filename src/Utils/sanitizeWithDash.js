/**
 * Sanitize a string with dashes without setting to lower case
 *
 * @returns string Sanitized string
 */
export function sanitizeWithDash(text)
{
    return text
    .trim()                            
    .replace(/[^a-z0-9 -]/g, "")       
    .replace(/\s+/g, "-")              
    .replace(/-+/g, "-"); 
}
