import Vuelidate from 'vuelidate'

Vue.use(Vuelidate);

import {required, minLength, maxLength, minValue, maxValue, between, numeric, alpha, alphaNum, email, decimal, integer, url} from 'vuelidate/lib/validators'

window.required = required;
window.minLength = minLength;
window.maxLength = maxLength;
window.minValue = minValue;
window.maxValue = maxValue;
window.between = between;
window.numeric = numeric;
window.alpha = alpha;
window.alphaNum = alphaNum;
window.email = email;
window.decimal = decimal;
window.integer = integer;
window.url = url;
