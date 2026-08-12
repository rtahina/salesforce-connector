/**
 * Locks post saving
 */
export const lockSaving = (lockName) => {
    wp.data.dispatch( 'core/editor' ).lockPostSaving(lockName);
}

/**
 * Unlock post saving
 */
export const unlockSaving = (lockName) => {
    wp.data.dispatch( 'core/editor' ).unlockPostSaving(lockName);
}