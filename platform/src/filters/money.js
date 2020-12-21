export default {
  name: 'money',
  filter: value => {
    if (!value) {
      return value
    }
    let amount = value.toString()
    amount = amount.replace('.', ',')
    return amount.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  }
}
