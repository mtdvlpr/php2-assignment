const email = document.getElementById('email')
const confirmEmail = document.getElementById('confirmemail')

const password = document.getElementById('pass')
const confirmPassword = document.getElementById('confirmpass')

const nameInput = document.getElementById('name')

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

function validateName(input: HTMLInputElement) {
  const pattern = /\d/
  if (pattern.exec(input.value) == null) {
    input.setCustomValidity('')
    input.style.backgroundColor = 'white'
    input.style.color = 'black'
  } else {
    input.setCustomValidity('Name should not contain numbers.')
    input.style.backgroundColor = '#fff6f6'
    input.style.color = 'red'
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

if (nameInput != null) {
  nameInput.addEventListener('change', () => {
    validateName(nameInput as HTMLInputElement)
  })

  nameInput.addEventListener('keyup', () => {
    validateName(nameInput as HTMLInputElement)
  })
}
