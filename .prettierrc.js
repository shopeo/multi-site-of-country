// Import the default config file and expose it in the project root.
// Useful for editor integrations.
const defaultConfig = require( '@wordpress/prettier-config' );

module.exports = {
	...defaultConfig,
	useTabs: true,
	tabWidth: 4,
	singleQuote: true,
};
