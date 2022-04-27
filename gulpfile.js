const gulp = require('gulp');
const del = require('gulp-clean');
const tar = require('gulp-tar');
const fs = require('fs');
const package = JSON.parse(fs.readFileSync('./package.json'));

function clean(){
  return gulp.src('dist/*.*').pipe(del());
}

function tarfiles(){
    return gulp.src(['./src/files/**/*'], { base: './src/files/' })
        .pipe(tar('files.tar'))
        .pipe(gulp.dest('./src/'));
}

function tartemplates(){
    return gulp.src(['./src/templates/**/*'], { base: './src/templates/' })
        .pipe(tar('templates.tar'))
        .pipe(gulp.dest('./src/'));
}

function tarpackage(){
    return gulp.src(['./src/language/*', './src/*.xml', './src/*.tar', './src/*.php', './src/*.sql'], { base: './src/' })
        .pipe(tar(package.name + '_v' + package.version + '.tar'))
        .pipe(gulp.dest('./dist/'));
}

function cleantars(){
    return gulp.src('src/*.tar').pipe(del());
  }

exports.build = gulp.series(clean, tarfiles, tartemplates, tarpackage, cleantars);