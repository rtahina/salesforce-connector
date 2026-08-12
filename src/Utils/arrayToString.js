/**
 * Converts an array to a string where items will be joined with new lines
 *
 * @returns string 
 */
export function arrayToString(array)
{
    let tmpArr = []

    if (Array.isArray( array ) && array.length > 0) {
        array.map( ( item, index ) => {
            const rowString = item.label + ':' + item.value
            tmpArr.push(rowString);
        });
    }

    return tmpArr.join("\n");
}
