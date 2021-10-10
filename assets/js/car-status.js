import axios from 'axios'

const selects = Array.from(document.querySelectorAll('.js-vehicule-status'))

selects.forEach(select => {
    select.addEventListener('change', async e => {
        const options = e.target.options
        const newStatusId = options[options.selectedIndex].value
        const vehiculeId = e.target.id.replace('js-vehicule-status-', '')

        try {
            const res = await axios.post('/api/changeCarStatus', {
                vehiculeId, newStatusId
            })

            if(res.status === 200){
                const successEl = document.getElementById('changeStatusSuccess')
                successEl.innerHTML = res.data.message
                successEl.style.display = 'none'
                successEl.style.display = 'block'
                setTimeout(() => {
                    successEl.style.display = 'none'
                }, 4000)
            }
        } catch (e) {
            console.error(e)
        }
    })
})

// const options = selects[0].options
// const value = options[options.selectedIndex].value

// console.log(value)