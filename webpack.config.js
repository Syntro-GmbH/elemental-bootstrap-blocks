const Path = require('path');
const webpackConfig = require('@syntro-opensource/webpack-config');
const {
    resolves,
    modules,
    plugins,
} = webpackConfig;


const ENV = process.env.NODE_ENV;
const PATHS = {
  // the root path, where your webpack.config.js is located.
  ROOT: Path.resolve(),
  // your node_modules folder name, or full path
  MODULES: 'node_modules',
  // thirdparty folder containing copies of packages which wouldn't be available on NPM
  THIRDPARTY: 'thirdparty',
  // the root path to your javascript source files
  SRC: Path.resolve('client/src'),
};

const config = [{
    name: 'mapblock',
    entry: {
      main: Path.resolve(__dirname, 'client/src/mapblock/bundle.js')
    },
    output: {
      path: Path.resolve(__dirname, 'client/dist/mapblock'),
      filename: 'bundle.js',
    },
    devtool: (ENV !== 'production') ? 'source-map' : '',
    resolve: resolves(ENV, PATHS),
    module: modules(ENV, PATHS),
    plugins: plugins(ENV, PATHS),
  }
];

module.exports = config;
