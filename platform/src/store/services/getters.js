export default {
  favoriteServiceList: state => {
    let fav = []
    if (state.servicelist.data.length) {
      state.servicelist.data.map(function(val, index) {
        if (val.favorite === true) {
          fav.push(state.servicelist.data[index])
        }
      })
      return fav
    } else {
      return []
    }
  }
}
