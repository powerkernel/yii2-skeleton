// Assembles your email content with HTML layout
module.exports = {
  options: {
    //layoutdir: '<%= paths.src %>/layouts',
    //partials: ['<%= paths.src %>/partials/**/*.hbs'],
    //helpers: ['<%= paths.src %>/helpers/**/*.js'],
    //data: ['<%= paths.src %>/data/*.{json,yml}'],
    flatten: true,
    ext : '.php'
  },
  pages: {
    src: ['<%= paths.dist %>/src/*.php'],
    dest: '<%= paths.dist %>/'
  }
};
