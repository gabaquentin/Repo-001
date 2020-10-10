/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLElement} content2
 * @property {HTMLElement} click
 */
export default class Filter {
    /**
     * @param {HTMLElement|null} element
     */

    constructor(element) {
        if (element === null){
            return
        }
        this.form = element.querySelector('.js-filter-form')
        this.content = element.querySelector('.js-filter-content')
        this.click = element.querySelector('.js-filter-click')
        this.bindEvents()
    }

    /**
     * Ajoute les comportements aux dufférents elements
     */
    bindEvents(){
        this.click.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault()
                this.loadUrl(a.getAttribute('href'))
            })
        })

        this.content.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault()
                this.loadUrl(a.getAttribute('href'))
            })
        })
    }

    async loadUrl(url) {
        const response = await fetch(url, {
            headers:{
                'X-Requested-With': 'XMLHtppRequest'
            }
        })
        if(response.status >= 200 && response.status < 300) {
            const data = await response.json()
            this.content.innerHTML =data.content
            history.replaceState({},'', url)
        }else{
            console.error(response)
        }
    }
}