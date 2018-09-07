module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	var PathConfig = require('./grunt-settings.js');

	// tasks
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		config: PathConfig,

		//clean files
		clean: {
			options: { force: true },
			temp: {
				files: [
					{
						src: [
							"<%= config.cssDir %>**/*.map",
							"<%= config.imgDir %>",
							"<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css.map",
							"./jpgtmp.jpg"
						]
					},
					{
						src: [
							"<%= config.cssDirTheme %>**/*.map",
							"<%= config.imgDirTheme %>",
							"<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css.map",
							"./jpgtmp.jpg"
						]
					}
				]
			}
		},

		postcss: {
			dev: {
				options: {
					map: true,
					processors: [
						require('autoprefixer-core')({browsers: ['last 4 version', 'Android 4']})
					]
				},
				files: [
					{
						src: [
							'<%= config.cssDir %>*.css',
							'<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css',
							'!<%= config.cssDir %>bootstrap.css',
							'!<%= config.cssDir %>bootstrap.min.css',
							'!<%= config.cssDir %>ie.css',
							'!<%= config.cssDir %>ie8.css'
						]
					},
					{
						src: [
							'<%= config.cssDirTheme %>*.css',
							'<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css',
							'!<%= config.cssDirTheme %>bootstrap.css',
							'!<%= config.cssDirTheme %>bootstrap.min.css',
							'!<%= config.cssDirTheme %>ie.css',
							'!<%= config.cssDirTheme %>ie8.css'
						]
					}
				]
			},
			dist: {
				options: {
					map: false,
					processors: [
						require('autoprefixer-core')({browsers: ['last 4 version', 'Android 4']})
					]
				},
				files: [
					{
						src: [
							'<%= config.cssDir %>*.css',
							'<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css',
							'!<%= config.cssDir %>bootstrap.css',
							'!<%= config.cssDir %>bootstrap.min.css',
							'!<%= config.cssDir %>ie.css',
							'!<%= config.cssDir %>ie8.css'
						]
					},
					{
						src: [
							'<%= config.cssDirTheme %>*.css',
							'<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css',
							'!<%= config.cssDirTheme %>bootstrap.css',
							'!<%= config.cssDirTheme %>bootstrap.min.css',
							'!<%= config.cssDirTheme %>ie.css',
							'!<%= config.cssDirTheme %>ie8.css'
						]
					}
				]
			}
		},

		//sass
		sass: {
			options: PathConfig.hasBower,
			dev: {
				options: {
					sourceMap: true,
					style: 'nested'
				},
				files: [
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDir %>',
						ext: '.css'
					},
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDirTheme %>',
						ext: '.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css'
					}
				]
			},
			dist: {
				options: {
					sourceMap: false,
					style: 'nested'
				},
				files: [
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDir %>',
						ext: '.css'
					},
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDirTheme %>',
						ext: '.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css'
					}
				]
			},
			min: {
				options: {
					sourceMap: false,
					outputStyle: 'compressed'
				},
				files: [
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDir %>',
						ext: '.min.css'
					},
					{
						expand: true,
						cwd: '<%= config.sassDir %>',
						src: ['**/*.scss', '!<%= config.sassMainFileName %>.scss'],
						dest: '<%= config.cssDirTheme %>',
						ext: '.min.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDir %><%= config.cssMainFileName %>.min.css'
					},
					{
						src: '<%= config.sassDir %><%= config.sassMainFileName %>.scss',
						dest: '<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.min.css'
					}
				]
			}
		},

		//watcher project
		watch: {
			options: {
				debounceDelay: 1,
				// livereload: true,
			},
			images: {
				files: ['<%= config.imgSourceDir %>**/*.*'],
				tasks: [/*'img:jpg', 'newer:pngmin:all', 'newer:svgmin'*/ 'newer:copy:images'],
				options: {
					spawn: false
				}
			},
			svgSprites: {
				files: ['<%= config.imgSourceDir %>svg-icons/*.*'],
				tasks: ['svgstore', 'svg2string'],
				options: {
					spawn: false
				}
			},
			css: {
				files: ['<%= config.sassDir %>**/*.scss'],
				tasks: ['sass:dev', 'postcss:dev'],
				options: {
					spawn: false,
				}
			},
			js: {
				files: ['<%= config.jsDir %>**/*.js'],
				tasks: ['newer:copy:js'],
				options: {
					spawn: false,
				}
			},
			fonts: {
				files: ['<%= config.fontsDir %>**/*.*'],
				tasks: ['newer:copy:fonts'],
				options: {
					spawn: false,
				}
			}
		},

		copy: {
			images: {
				files: [
					{
						expand: true,
						cwd: '<%= config.imgSourceDir %>',
						src: '**',
						dest: '<%= config.imgDir %>',
						filter: 'isFile',
					},
					{
						expand: true,
						cwd: '<%= config.imgSourceDir %>',
						src: '**',
						dest: '<%= config.imgDirTheme %>',
						filter: 'isFile',
					}
				]
			},
			js: {
				expand: true,
				cwd: '<%= config.jsDir %>',
				src: '**',
				dest: '<%= config.jsDirTheme %>',
				filter: 'isFile',
			},
			fonts: {
				expand: true,
				cwd: '<%= config.fontsDir %>',
				src: '**',
				dest: '<%= config.fontsDirTheme %>',
				filter: 'isFile',
			}
		},

		imagemin: {
			dynamic: {
				files: [
					{
						expand: true,
						cwd: '<%= config.imgSourceDir %>',
						src: ['**/*.{jpg,gif}'],
						dest: "<%= config.imgDir %>",
					},
					{
						expand: true,
						cwd: '<%= config.imgSourceDir %>',
						src: ['**/*.{jpg,gif}'],
						dest: "<%= config.imgDirTheme %>"
					}
				]
			}
		},

		svgmin: {
			options: {
				plugins: [
					{
						removeViewBox: false
					}, {
						removeUselessStrokeAndFill: false
					}
				]
			},
			dist: {
				files: [
					{
						expand: true,
						src: ['**/*.svg'],
						cwd: '<%= config.imgSourceDir %>',
						dest: "<%= config.imgDir %>",
					},
					{
						expand: true,
						src: ['**/*.svg'],
						cwd: '<%= config.imgSourceDir %>',
						dest: "<%= config.imgDirTheme %>"
					}
				]
			}
		},

		svgstore: {
			options: {
				prefix : 'icon-', // This will prefix each ID
				svg: { // will add and overide the the default xmlns="http://www.w3.org/2000/svg" attribute to the resulting SVG
					viewBox : '0 0 100 100',
					xmlns: 'http://www.w3.org/2000/svg'
				},
				cleanup: ['fill']
			},
			your_target: {
				files: {
					'<%= config.imgDir %>svg-sprites/sprite.svg': ['<%= config.imgDir %>svg-icons/*.svg'],
					'<%= config.imgDirTheme %>svg-sprites/sprite.svg': ['<%= config.imgDirTheme %>svg-icons/*.svg']
				},
			},
		},

		svg2string: {
			elements: {
				options: {
					template: '(window.SVG_SPRITES = window.SVG_SPRITES || {})["[%= filename %]"] = [%= content %];',
					wrapLines: false
				},
				files: {
					'<%= config.jsDir %>svg-sprites.js': ['<%= config.imgDir %>svg-sprites/sprite.svg'],
					'<%= config.imgDirTheme %>svg-sprites/sprite.svg': ['<%= config.imgDirTheme %>svg-icons/*.svg']
				}
			}
		},

		// lossy image optimizing (compress png images with pngquant)
		pngmin: {
			all: {
				options: {
					ext: '.png',
					force: true
				},
				files: [
					{
						expand: true,
						src: ['**/*.png'],
						cwd: '<%= config.imgSourceDir %>',
						dest: "<%= config.imgDir %>",
					},
					{
						expand: true,
						src: ['**/*.png'],
						cwd: '<%= config.imgSourceDir %>',
						dest: "<%= config.imgDirTheme %>"
					}
				]
			},
		},

		//Keep multiple browsers & devices in sync when building websites.
		browserSync: {
			dev: {
				bsFiles: {
					src : ['*.html','<%= config.cssDir %>*.css', '*.css']
				},
				options: {
					server: {
						baseDir: "./",
						index: "index.html",
						directory: true
					},
					watchTask: true
				}
			}
		},

		notify: {
			options: {
				enabled: true,
				max_js_hint_notifications: 5,
				title: "WP project"
			},
			watch: {
				options: {
					title: 'Task Complete',  // optional
					message: 'SASS finished running', //required
				}
			},
		},

		csscomb: {
			all: {
				expand: true,
				files: [
					{
						src: [
							'<%= config.cssDir %>*.css',
							'<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css',
							'!<%= config.cssDir %>bootstrap.css',
							'!<%= config.cssDir %>ie.css',
							'!<%= config.cssDir %>ie8.css'
						]
					},
					{
						src: [
							'<%= config.cssDirTheme %>*.css',
							'<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css',
							'!<%= config.cssDirTheme %>bootstrap.css',
							'!<%= config.cssDirTheme %>ie.css',
							'!<%= config.cssDirTheme %>ie8.css'
						]
					}
				],
				ext: '.css'
			},
			dist: {
				expand: true,
				files: [
					{
						'<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css' : '<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css',
						'<%= config.cssMainFileDir %>bootstrap.css' : '<%= config.cssMainFileDir %>bootstrap.css',
						'<%= config.cssMainFileDir %>bootstrap-extended.css' : '<%= config.cssMainFileDir %>bootstrap-extended.css'
					},
					{
						'<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css' : '<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css',
						'<%= config.cssMainFileDirTheme %>bootstrap.css' : '<%= config.cssMainFileDirTheme %>bootstrap.css',
						'<%= config.cssMainFileDirTheme %>bootstrap-extended.css' : '<%= config.cssMainFileDirTheme %>bootstrap-extended.css'
					}
				]
			}
		},

		cmq: {
			options: {
				log: false
			},
			all: {
				files: [
					{
						expand: true,
						src: ['**/*.css', '!bootstrap.css'],
						cwd: '<%= config.cssDir %>',
						dest: '<%= config.cssDir %>'
					},
					{
						expand: true,
						src: ['**/*.css', '!bootstrap.css'],
						cwd: '<%= config.cssDirTheme %>',
						dest: '<%= config.cssDirTheme %>'
					}
				]
			},
			dist: {
				files: [
					{
						'<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css' : '<%= config.cssMainFileDir %><%= config.cssMainFileName %>.css'
					},
					{
						'<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css' : '<%= config.cssMainFileDirTheme %><%= config.cssMainFileNameTheme %>.css'
					}
				]
			}
		},

		'sftp-deploy': {
			build: {
				auth: {
					host: '<%= config.sftpServer %>',
					port: '<%= config.sftpPort %>',
					authKey: {
						"username": "<%= config.sftpLogin %>",
						"password": "<%= config.sftpPas %>"
					}
				},
				cache: 'sftpCache.json',
				src: 'css',
				dest: '<%= config.sftpDestination %>',
				serverSep: '/',
				concurrency: 4,
				progress: true
			}
		}

	});

// run task
//dev 
	//watch
	grunt.registerTask('w', ['watch']);

	//browser sync
	grunt.registerTask('bs', ['browserSync']);

	//watch + browser sync
	grunt.registerTask('dev', ['browserSync', 'watch']);

	//create svg sprite
	grunt.registerTask('svgsprite', ['svgmin', 'svgstore', 'svg2string']);

	grunt.registerTask('default', ['dev']);

	// upload to server
	grunt.registerTask('sftp', ['sftp-deploy']);

//finally 
	//css beautiful
	grunt.registerTask('cssbeauty', ['sass:dist', 'cmq:dist', 'postcss:dist', 'csscomb:dist']);

	//img minify
	grunt.registerTask('imgmin', ['imagemin', 'pngmin:all', 'svgmin']);

	//final build
	// grunt.registerTask('dist', ['clean:temp', 'imgmin', 'cssbeauty', 'minified']);
	grunt.registerTask('dist', ['clean:temp', 'imgmin', 'cssbeauty']);
};