import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const generateColorClass = (variable) => {
	return ({ opacityValue }) =>
	opacityValue
	? `rgba(var(--${variable}), ${opacityValue})`
	: `rgb(var(--${variable}))`
}

const textColor = {
	blue: generateColorClass('text-blue'),
	bluedark: generateColorClass('text-bluedark'),
	bluemedium: generateColorClass('text-bluemedium'),
	dark: generateColorClass('text-dark'),
	green: generateColorClass('text-green'),
	greylight: generateColorClass('text-greylight'),
	hoverlight: generateColorClass('text-hoverlight'),
	light: generateColorClass('text-light'),
	red: generateColorClass('text-red'),
	yellow: generateColorClass('text-yellow'),
}

const borderColor = {
	bluemedium: generateColorClass('border-bluemedium'),
	greydark: generateColorClass('border-greydark'),
	greylight: generateColorClass('border-greylight'),
}

const backgroundColor = {
	blue: generateColorClass('bg-blue'),
	bluelight: generateColorClass('bg-bluelight'),
	bluemedium: generateColorClass('bg-bluemedium'),
	dark: generateColorClass('bg-dark'),
	light: generateColorClass('bg-light'),
	greylight: generateColorClass('bg-greylight'),
	greymid: generateColorClass('bg-greymid'),
	hoverlight: generateColorClass('bg-hoverlight'),
	red: generateColorClass('bg-red'),
	sky: generateColorClass('bg-sky'),
	yellow: generateColorClass('bg-yellow'),
	yellowlight: generateColorClass('bg-yellowlight'),
}

/** @type {import('tailwindcss').Config} */
export default {
	darkMode: 'class',
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
	],
	theme: {
		extend: {
			textColor, backgroundColor, borderColor,
			fontFamily: {
				sans: ['VerdanaWoffB64', ...defaultTheme.fontFamily.sans],
			},
		},
		colors: {
		transparent: 'transparent',
		current: 'currentColor',
		},
	},
	plugins: [
		forms, 
		typography
	],
};
