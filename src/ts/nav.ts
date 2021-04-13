function changeActivePage(target: string) {
  // Remove the .active class from each item
  document.querySelectorAll('#main-nav .active').forEach((element) => {
    element.classList.remove('active')
  })

  // Add .active class to target item
  document.querySelectorAll('#main-nav a').forEach((element) => {
    if (element.getAttribute('href') === target) {
      element.classList.add('active')
    }
  })
}

changeActivePage(window.location.pathname)
