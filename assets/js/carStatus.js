import axios from 'axios'

const selects = Array.from(document.querySelectorAll('.js-vehicule-status'))

selects.forEach(select => {
    select.addEventListener('change', e => changeCarStatus(e))
})

const changeCarStatus = async e => {
    const options = e.target.options
    const newStatusId = options[options.selectedIndex].value
    const vehiculeId = e.target.id.replace('js-vehicule-status-', '')

    const htmlElement = document.getElementById('js-changeStatus')

    try {
        const res = await axios.post('/api/changeCarStatus', {
            vehiculeId, newStatusId
        })

        if (res.status === 200) {
            showMessageBox(htmlElement, 'alert-success', `L'état du véhicule <strong>${res.data.name}</strong> a été modifié en <strong>${res.data.status}</strong>.`)
        }

    } catch (e) {
        console.error(e)
        showMessageBox(htmlElement, 'alert-danger', `L'état du véhicule n'a pas été modifié. Il y a un problème avec le serveur !`)
    }
}

const showMessageBox = (htmlElement, className, message) => {
    htmlElement.classList.add(className)
    htmlElement.innerHTML = message
    htmlElement.style.display = 'block'
    setTimeout(() => {
        htmlElement.style.display = 'none'
        htmlElement.classList.remove(className)
    }, 4000)
}