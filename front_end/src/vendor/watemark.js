'use strict'

const watermark = {}

const setWatermark = (str) => {
    const id = '1.23452384164.123412415'

    if (document.getElementById(id) !== null) {
        document.body.removeChild(document.getElementById(id))
    }

    const can = document.createElement('canvas')
    can.width = 350
    can.height = 350

    const cans = can.getContext('2d')
    cans.rotate(-20 * Math.PI / 180)
    cans.font = '25px Vedana'
    cans.fillStyle = 'rgba(200, 200, 200, 0.05)'
    cans.textAlign = 'left'
    cans.textBaseline = 'Middle'
    cans.fillText(str, can.width / 3, can.height / 2)

    const div = document.createElement('div')
    div.id = id
    div.style.pointerEvents = 'none'
    div.style.top = '70px'
    div.style.left = '90px'
    div.style.position = 'fixed'
    div.style.zIndex = '100000'
    div.style.width = document.documentElement.clientWidth - 50 + 'px'
    div.style.height = document.documentElement.clientHeight - 50 + 'px'
    div.style.background = 'url(' + can.toDataURL('image/png') + ') left top repeat'
    document.body.appendChild(div)
    return id
}

// 该方法只允许调用一次
watermark.set = (str) => {
    let id = setWatermark(str)
    setInterval(() => {
        if (document.getElementById(id) === null) {
            id = setWatermark(str)
        }
    }, 500)
    window.onresize = () => {
        setWatermark(str)
    }
}

export default watermark
