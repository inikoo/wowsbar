import { faEnvelope as fasEnvelope, faPhone as fasPhone, faBuilding as fasBuilding, faCircle as fasCircle, faMap as fasMap, faUser as fasUser } from '@/../private/pro-solid-svg-icons';
import { faEnvelope as falEnvelope, faPhone as falPhone, faBuilding as falBuilding, faCircle as falCircle, faMap as falMap, faUser as falUser } from '@/../private/pro-light-svg-icons';
import { faEnvelope as farEnvelope, faPhone as farPhone, faBuilding as farBuilding, faCircle as farCircle, faMap as farMap, faUser as farUser, faDotCircle } from '@/../private/pro-regular-svg-icons';
import { faEnvelope as fadEnvelope, faPhone as fadPhone, faBuilding as fadBuilding, faCircle as fadCircle, faMap as fadMap, faUser as fadUser } from '@/../private/pro-duotone-svg-icons';
import { faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp } from "@fortawesome/free-brands-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'

library.add( 
faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp, 
fasEnvelope, fasPhone, fasBuilding, fasCircle, fasMap, fasUser,
falEnvelope, falPhone, falBuilding, falCircle, falMap, falUser,
farEnvelope, farPhone, farBuilding, farCircle, farMap, farUser, faDotCircle,
fadEnvelope, fadPhone, fadBuilding, fadCircle, fadMap, fadUser,
)

export default {
    fontAwesome: {
      title: 'Font Awesome',
      variants: {
        regular: {
          title: 'Regular',
          prefix: 'far fa-',
          iconstyle: 'fa-regular',
          listicon: 'fab fa-font-awesome-alt',
          icons: [
            'far fa-envelope',
            'far fa-phone',
            'far fa-building',
            'far fa-circle',
            'far fa-map',
            'far fa-user',
            'far fa-dot-circle'
          ]
        },
        solid: {
          title: 'Solid',
          prefix: 'fas fa-',
          iconstyle: 'fa-solid',
          listicon: 'fab fa-font-awesome',
          icons: [
            'fas fa-envelope',
            'fas fa-phone',
            'fas fa-building',
            'fas fa-circle',
            'fas fa-map',
            'fas fa-user',
            'fas fa-star'
          ]
        },
        brands: {
          title: 'Brands',
          prefix: 'fab fa-',
          iconstyle: 'fa-brands',
          listicon: 'fab fa-font-awesome-flag',
          icons: [
            'fab fa-tiktok',
            'fab fa-facebook',
            'fab fa-facebook-f',
            'fab fa-instagram',
            'fab fa-square-instagram',
            'fab fa-square-whatsapp',
            'fab fa-whatsapp',
          ]
        }
      }
    }
  }