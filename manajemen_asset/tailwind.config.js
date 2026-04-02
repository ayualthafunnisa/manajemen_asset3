/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // PRIMARY - UNGU seperti di dashboard Jobie
        primary: {
          DEFAULT: '#7c3aed', // Ungu utama yang terlihat di dashboard
          50: '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6',
          600: '#7c3aed', // Warna utama
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
          950: '#2e1065',
        },
        // SECONDARY - Biru untuk kontras
        secondary: {
          DEFAULT: '#3b82f6', // Biru
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        },
        // ACCENT - Hijau untuk status positif
        accent: {
          DEFAULT: '#10b981', // Hijau untuk status accepted
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          300: '#6ee7b7',
          400: '#34d399',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
        },
        // Warna untuk chart dan statistik
        chart: {
          purple: '#8b5cf6',
          blue: '#3b82f6',
          green: '#10b981',
          orange: '#f97316',
          pink: '#ec4899',
        },
        // Warna untuk status dashboard
        status: {
          success: '#10b981',
          pending: '#f59e0b',
          rejected: '#ef4444',
          interview: '#3b82f6',
          applied: '#8b5cf6',
        },
        success: {
          DEFAULT: '#10b981',
          light: '#34d399',
          dark: '#059669',
        },
        warning: {
          DEFAULT: '#f59e0b',
          light: '#fbbf24',
          dark: '#d97706',
        },
        danger: {
          DEFAULT: '#ef4444',
          light: '#f87171',
          dark: '#dc2626',
        },
        info: {
          DEFAULT: '#3b82f6',
          light: '#60a5fa',
          dark: '#2563eb',
        },
        // Neutral - Untuk teks dan background
        neutral: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
        // Background colors
        background: {
          DEFAULT: '#f8fafc',
          card: '#ffffff',
          sidebar: '#ffffff',
          header: '#ffffff',
          muted: '#f1f5f9',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        heading: ['Montserrat', 'Inter', 'sans-serif'],
        display: ['Montserrat', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
      },
      boxShadow: {
        // Shadow dengan warna ungu
        'dp': '0 4px 6px -1px rgba(124, 58, 237, 0.1), 0 2px 4px -1px rgba(124, 58, 237, 0.06)',
        'dp-lg': '0 10px 15px -3px rgba(124, 58, 237, 0.15), 0 4px 6px -2px rgba(124, 58, 237, 0.1)',
        'dp-xl': '0 20px 25px -5px rgba(124, 58, 237, 0.2), 0 10px 10px -5px rgba(124, 58, 237, 0.1)',
        'card': '0 4px 14px 0 rgba(124, 58, 237, 0.08)',
        'card-hover': '0 8px 25px 0 rgba(124, 58, 237, 0.15)',
        'soft': '0 2px 8px 0 rgba(0, 0, 0, 0.06)',
        'soft-lg': '0 4px 16px 0 rgba(0, 0, 0, 0.08)',
        'sidebar': '2px 0 8px 0 rgba(0, 0, 0, 0.05)',
      },
      backgroundImage: {
        // Gradient dengan warna ungu
        'gradient-primary': 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)',
        'gradient-secondary': 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
        'gradient-accent': 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
        'gradient-card': 'linear-gradient(180deg, #ffffff 0%, #f8fafc 100%)',
        'gradient-header': 'linear-gradient(90deg, #7c3aed 0%, #8b5cf6 100%)',
        'gradient-sidebar': 'linear-gradient(180deg, #ffffff 0%, #f8fafc 100%)',
        'gradient-chart': 'linear-gradient(180deg, #8b5cf6 0%, #3b82f6 50%, #10b981 100%)',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-out',
        'fade-in-up': 'fadeInUp 0.4s ease-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'slide-down': 'slideDown 0.3s ease-out',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'bounce-slow': 'bounce 2s infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
      // Layout untuk dashboard
      gridTemplateColumns: {
        'dashboard': '280px 1fr',
        'dashboard-sm': '240px 1fr',
        'stats-grid': 'repeat(auto-fit, minmax(240px, 1fr))',
        'jobs-grid': 'repeat(auto-fill, minmax(320px, 1fr))',
        'companies-grid': 'repeat(auto-fill, minmax(200px, 1fr))',
      },
      spacing: {
        'sidebar': '280px',
        'sidebar-sm': '240px',
        'header': '64px',
      },
      borderRadius: {
        'xl': '0.75rem',
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}