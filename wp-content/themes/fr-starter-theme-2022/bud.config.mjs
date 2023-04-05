// @ts-check

/**
 * Build configuration
 *
 * @see {@link https://bud.js.org/guides/getting-started/configure}
 * @param {import('@roots/bud').Bud} app
 */
export default async (app) => {
  app
    /**
     * Application entrypoints
     */
    .entry({
      app: ["@scripts/app", "@styles/app"],
      editor: ["@scripts/editor", "@styles/editor"],
    })

    /**
     * Directory contents to be included in the compilation
     */
    .assets(["images"])

    /**
     * Matched files trigger a page reload when modified
     */
    .watch(["resources/views/**/*", "app/**/*", "resources/**/*", "resources/styles/**/*"])

    /**
     * Proxy origin (`WP_HOME`)
     */
    .proxy(app.env.get('PROXY_ORIGIN'))

    /**
     * Development origin
     */
    .serve(app.env.get('DEV_ORIGIN'))

    /**
     * URI of the `public` directory
     */
    .setPublicPath(app.env.get('PUBLIC_PATH'));
};
