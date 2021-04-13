const email = document.getElementById('email')
const confirmEmail = document.getElementById('confirmemail')

const password = document.getElementById('pass')
const confirmPassword = document.getElementById('confirmpass')

function validateInputs(input: HTMLInputElement, confirm: HTMLInputElement) {
  if (input.value !== confirm.value) {
    confirm.setCustomValidity('Values do not match.')
    confirm.style.backgroundColor = '#fff6f6'
    confirm.style.color = 'red'
  } else {
    confirm.setCustomValidity('')
    confirm.style.backgroundColor = 'white'
    confirm.style.color = 'black'
  }
}

if (email != null && confirmEmail != null) {
  email.addEventListener('change', () => {
    validateInputs(email as HTMLInputElement, confirmEmail as HTMLInputElement)
  })

  confirmEmail.addEventListener('keyup', () => {
    validateInputs(email as HTMLInputElement, confirmEmail as HTMLInputElement)
  })
}

if (password != null && confirmPassword != null) {
  password.addEventListener('change', () => {
    validateInputs(password as HTMLInputElement, confirmPassword as HTMLInputElement)
  })

  confirmPassword.addEventListener('keyup', () => {
    validateInputs(password as HTMLInputElement, confirmPassword as HTMLInputElement)
  })
}
