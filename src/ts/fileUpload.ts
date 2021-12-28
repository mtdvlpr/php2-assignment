const inputs = document.querySelectorAll('.input-pic')
Array.prototype.forEach.call(inputs, (input: HTMLInputElement) => {
  const label = input.nextElementSibling

  if (label != null) {
    const labelVal = label.innerHTML
    input.addEventListener('change', (_e) => {
      let fileName = ''
      fileName = input.value.split('\\').pop() ?? 'file'
      const span = label.querySelector('span')
      if (fileName && span != null) span.innerHTML = fileName
      else label.innerHTML = labelVal
    })
  }
})
