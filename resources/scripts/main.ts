import Guide from '@scripts/guide'
import '@styles/main.scss'

const { DEV } = import.meta.env

/**
 * Enable guide in dev mode
 */
DEV && new Guide().start()
