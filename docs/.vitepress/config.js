import { defineConfig } from 'vitepress'; // eslint-disable-line import/no-extraneous-dependencies
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
   title: 'Moodle Plugin: Course Visibility',
   description: 'Documentation for the Course Visibility plugin.',
   ignoreDeadLinks: 'localhostLinks',
   outDir: '../dist/docs',
   vite: {
      ssr: {
         noExternal: [ '@uicpharm/vitepress-theme' ],
      },
      plugins: [
         viteStaticCopy({
            targets: [ { src: '../node_modules/@uicpharm/vitepress-theme/public/uic-logo.svg', dest: '.' } ],
         }),
      ],
   },
   themeConfig: {
      logo: '/uic-logo.svg',
      outline: 'deep',
      nav: [
         { text: 'Download', link: 'https://github.com/uicpharm/moodle-local_coursevis/releases' },
      ],
      socialLinks: [
         { icon: 'github', link: 'https://github.com/uicpharm/moodle-local_coursevis' },
      ],
   },
});
