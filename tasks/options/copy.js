module.exports = {
	// Copy the theme to a versioned release directory
	main: {
		expand: true,
		src:  [
			'**',
			'!**/.*',
			'!**/readme.md',
			'!node_modules/**',
			'!vendor/**',
			'!tasks/**',
			'!tests/**',
      '!release/**',
			'!conf/**',
      '!assets/css/sass/**',
			'!assets/css/src/**',
			'!assets/js/src/**',
			'!images/src/**',
			'!bootstrap.php',
			'!bootstrap.php.dist',
			'!bower.json',
			'!composer.json',
			'!composer.lock',
			'!Gruntfile.js',
			'!package.json',
			'!phpunit.xml',
			'!phpunit.xml.dist'
		],
		dest: 'release/<%= pkg.version %>/'
	}
};
