module.exports = {
  devServer: {
    disableHostCheck: true,
    public: process.env.VUE_APP_DOMAIN
  },
  pwa: {
    name: 'Zen Office'
  }
}
