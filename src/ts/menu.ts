document.getElementById('menu-icon')?.addEventListener('click', () => {
  const nav = document.getElementById('myTopNav')
  if (nav != null && nav.className === 'topnav') {
    nav.className += ' responsive'
  } else if (nav != null) {
    nav.className = 'topnav'
  }
})
