/* eslint no-param-reassign: ['error', { props: false }] */
import fs from 'fs';
import gulp from 'gulp';
import path from 'path';
import rename from 'gulp-rename';
import zip from 'gulp-zip';

const phpGlobs = [ 'src/**/*.php' ];
const { name: packageName } = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
const frankenstyleName = packageName.replace(/^moodle-/, '');

function getPluginVersion() {
   const versionFile = fs.readFileSync('src/version.php', 'utf8');
   const match = versionFile.match(/^\s*\$plugin->version\s*=\s*(\d+);/m);
   if (!match) throw new Error('version.php does not contain a valid $plugin->version.');
   return match[1];
}

function prefixPath(prefix) {
   return rename((filePath) => {
      filePath.dirname = path.join(prefix, filePath.dirname);
   });
}

export function pkg() {
   return gulp.src(phpGlobs, { base: 'src' })
      .pipe(prefixPath(frankenstyleName))
      .pipe(zip(`${frankenstyleName}_${getPluginVersion()}.zip`))
      .pipe(gulp.dest('dist/package'));
}

export function send() {
   const defaultDest = 'dist/dev';
   const dest = process.env.DEST ?? defaultDest;

   if (!process.env.DEST) {
      console.warn('To send the package to your Moodle instance, set the DEST environment variable. Example: DEST=/my/path');
   }

   return gulp.src(phpGlobs, { base: 'src' })
      // If frankenstyle is local_coursevis, we want it to become local/coursevis
      .pipe(prefixPath(frankenstyleName.replaceAll('_', '/')))
      .pipe(gulp.dest(dest));
}

export function watch() {
   gulp.watch(phpGlobs, send);
}

export const build = gulp.parallel(pkg);
export default gulp.series(send, watch);
