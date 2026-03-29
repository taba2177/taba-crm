/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,ts}",
  ],
  theme: {
    extend: {
      colors: {
        // Navy palette — derived from company profile dark navy #151A25
        wood: {
          50: '#F1F3F6',
          100: '#E0E4EB',
          200: '#C2C9D6',
          300: '#9BA6B8',
          400: '#6F7E96',
          500: '#536179',
          600: '#3D4B63',
          700: '#2A3447',
          800: '#1E2533',
          900: '#151A25',
          950: '#0B0E14',
        },
        // Gold accent — derived from company profile gold #E8B84B
        accent: {
          DEFAULT: '#E8B84B',
          hover: '#D4A33A',
          light: '#FDF6E7'
        },
        surface: {
          DEFAULT: '#FAFBFC',
          dark: '#0E1118'
        }
      },
      fontFamily: {
        serif: ['Amiri', 'Playfair Display', 'serif'],
        sans: ['Alexandria', 'Inter', 'sans-serif'],
        display: ['Tajawal', 'sans-serif'],
      },
      animation: {
        'fade-in-up': 'fadeInUp 1.2s cubic-bezier(0.19, 1, 0.22, 1) forwards',
        'fade-in': 'fadeIn 1.5s ease-out forwards',
        'scale-in': 'scaleIn 1.5s cubic-bezier(0.19, 1, 0.22, 1) forwards',
        'slide-in-right': 'slideInRight 1s cubic-bezier(0.19, 1, 0.22, 1) forwards',
        'slide-in-left': 'slideInLeft 1s cubic-bezier(0.19, 1, 0.22, 1) forwards',
        'draw-line': 'drawLine 1.5s cubic-bezier(0.19, 1, 0.22, 1) forwards',
        'float': 'float 6s ease-in-out infinite',
        'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
        'count-up': 'countUp 0.6s ease-out forwards',
        'marquee': 'marquee 30s linear infinite',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(40px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        scaleIn: {
          '0%': { opacity: '0', transform: 'scale(1.05)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        slideInRight: {
          '0%': { opacity: '0', transform: 'translateX(80px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        slideInLeft: {
          '0%': { opacity: '0', transform: 'translateX(-80px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        drawLine: {
          '0%': { width: '0%' },
          '100%': { width: '100%' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-12px)' },
        },
        pulseGlow: {
          '0%, 100%': { boxShadow: '0 0 20px rgba(232, 184, 75, 0.2)' },
          '50%': { boxShadow: '0 0 40px rgba(232, 184, 75, 0.5)' },
        },
        countUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        marquee: {
          '0%': { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-50%)' },
        },
      },
      backgroundImage: {
        'noise': "url('/assets/noise.png')",
      }
    },
  },
  plugins: [],
}
