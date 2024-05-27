const mix = require( 'laravel-mix' );

mix.js( 'src/assets/js/admin.js', 'assets/js' )
	.js( 'src/assets/js/app.js', 'assets/js' )
	.sass(
		'src/assets/scss/style.scss',
		'assets/css',
		[],
		[
			require( 'postcss-import' ),
			require( 'tailwindcss' ),
			require( 'autoprefixer' ),
		]
	)
	.sass(
		'src/assets/scss/style-rtl.scss',
		'assets/css',
		[],
		[
			require( 'postcss-import' ),
			require( 'tailwindcss' ),
			require( 'rtlcss' ),
			require( 'autoprefixer' ),
		]
	);
