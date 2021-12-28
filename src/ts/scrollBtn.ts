/* Scroll back button */
const scrollBtn = document.getElementById('scroll-btn')
if (scrollBtn != null) {
  window.addEventListener('scroll', () => {
    if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
      scrollBtn.style.display = 'block'
      if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 110) {
        scrollBtn.style.position = 'absolute'
        scrollBtn.style.bottom = '160px'
      } else {
        scrollBtn.style.position = 'fixed'
        scrollBtn.style.bottom = '40px'
      }
    } else {
      scrollBtn.style.display = 'none'
    }
  })
}

document.getElementById('scroll-btn')?.addEventListener('click', () => {
  document.body.scrollTop = 0
  document.documentElement.scrollTop = 0
})
