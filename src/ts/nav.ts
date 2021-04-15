document.getElementById('menu-icon')?.addEventListener('click', () => {
  const nav = document.getElementById('main-nav')
  if (nav != null) {
    nav.classList.toggle('responsive')
  }
})

function changeActivePage(target: string) {
  // Remove the .active class from each item
  document.querySelectorAll('#main-nav > a.active').forEach((element) => {
    element.classList.remove('active')
  })

  // Add .active class to target item
  document.querySelectorAll('#main-nav > a').forEach((element) => {
    if (element.getAttribute('href') === target) {
      element.classList.add('active')
    }
  })
}

changeActivePage(window.location.pathname)
