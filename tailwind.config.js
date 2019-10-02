module.exports = {
  theme: {
    container:  {
      center: true,
      padding: '2rem'
    },
    extend: {
      colors: {
        white: {
          default: '#fff',
          '10': 'rgba(255, 255, 255, .1)',
          '20': 'rgba(255, 255, 255, .2)',
          '30': 'rgba(255, 255, 255, .3)',
          '40': 'rgba(255, 255, 255, .4)',
          '50': 'rgba(255, 255, 255, .5)',
          '60': 'rgba(255, 255, 255, .6)',
          '70': 'rgba(255, 255, 255, .7)',
          '80': 'rgba(255, 255, 255, .8)',
          '90': 'rgba(255, 255, 255, .9)'
        },
        black: {
          default: '#000',
          '10': 'rgba(0, 0, 0, .1)',
          '20': 'rgba(0, 0, 0, .2)',
          '30': 'rgba(0, 0, 0, .3)',
          '40': 'rgba(0, 0, 0, .4)',
          '50': 'rgba(0, 0, 0, .5)',
          '60': 'rgba(0, 0, 0, .6)',
          '70': 'rgba(0, 0, 0, .7)',
          '80': 'rgba(0, 0, 0, .8)',
          '90': 'rgba(0, 0, 0, .9)'
        },
        magenta: {
          900: '#3f0f3f',
          800: '#4e004e',
          700: '#760076',
          600: '#b100b1',
          500: '#eb00eb',
          400: '#ff27ff',
          300: '#ff76ff',
          200: '#ffc4ff',
          100: '#ffebff',
        }
      },
      width: {
        '72': '18rem',
        '80': '20rem',
        '96': '24rem',
      },
      maxWidth: {
        '16': '4rem',
        '32': '8rem',
        '64': '16rem',
      },
      minWidth: {
        '32': '8rem',
        '48': '12rem',
        '64': '16rem'
      },
      height: {
        current: '1em'
      },
      maxHeight: {
        'screen-1/2': '50vh',
      }
    }
  },
  variants: {
    textColor: ['responsive', 'focus', 'hover', 'group-hover']
  },
  plugins: []
}
