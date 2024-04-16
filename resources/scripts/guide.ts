export default class Guide {
    namespace: string = 'guide'

    constructor() {
        const guide = document.createElement('div')
        const wrapper = document.createElement('div')
        const container = document.createElement('div')
        const count = 12

        console.info('[%s] bootstrap', this.namespace)

        guide.classList.add('guide')
        container.classList.add('container')
        wrapper.classList.add('guide-wrapper')

        for (let i = 0; i < count; i++) {
            const element = document.createElement('div')

            element.classList.add('guide-element')
            wrapper.appendChild(element)
        }

        container.appendChild(wrapper)
        guide.appendChild(container)
        document.body.appendChild(guide)
    }

    start(): void {
        console.info('[%s] started', this.namespace)

        const guide = localStorage.getItem('guide')

        guide && document.querySelector('.guide')?.classList.add('active')

        document.addEventListener('keyup', (e: KeyboardEvent): void => {
            if (e.code === 'Digit9') {
                const guide = localStorage.getItem('guide')

                document.querySelector('.guide')?.classList.toggle('active')

                if (guide) {
                    localStorage.removeItem('guide')
                } else {
                    localStorage.setItem('guide', '1')
                }
            }

            if (e.code === 'Digit0') {
                document.body.classList.toggle('debug')
            }
        })
    }
}
