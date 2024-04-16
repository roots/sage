import Router from './utils/Router'
import components from './components'
import pages from './pages'

const routes = new Router({
  components,
  pages,
})

window.addEventListener('DOMContentLoaded', () => {
  routes.loadEvents()
  components.init()
  pages.init()
})
