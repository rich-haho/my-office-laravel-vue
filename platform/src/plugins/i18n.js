import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

export const defaultLocale = 'fr'

const i18n = new VueI18n({
  locale: defaultLocale,
  messages: {}
})

/**
 * @param {String} locale
 */
export async function loadMessages(locale) {
  if (Object.keys(i18n.getLocaleMessage(locale)).length === 0) {
    const messages = await import(`@/lang/${locale}/index.js`)
    i18n.setLocaleMessage(locale, messages)
  }
  if (i18n.locale !== locale) {
    i18n.locale = locale
  }
}
export default i18n
