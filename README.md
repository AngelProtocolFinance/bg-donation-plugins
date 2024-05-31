# bg-donation-plugins
Plugins for 3rd party platforms to help nonprofits easily use the BG Donation Form where ever they host their webiste.

## Building a Plugin
There is a build script wrapper location at `./build.sh` that handles the build steps of the plugin for the given platform.

### Example: Building the WordPress plugin
1. Run the build script with the argument `wordpress`
```shell 
./build.sh wordpress
```
2. If all went well, the new plugin package artifact will be found in the top-level output folder (`.out/`).

