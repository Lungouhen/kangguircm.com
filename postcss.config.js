const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
  map: process.env.NODE_ENV !== 'production' ? { inline: false } : false,
  plugins: [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-preset-env')({
      stage: 2,
      features: {
        'nesting-rules': true,
        'custom-media-queries': true,
        'custom-properties': true,
        'logical-properties-and-values': true,
        'gap-properties': true,
      },
      autoprefixer: { grid: true },
      browsers: 'last 2 versions',
    }),
    require('postcss-nested'),
    require('autoprefixer'),
    isProduction && require('cssnano')({
      preset: ['default', {
        discardComments: { removeAll: true },
        normalizeWhitespace: true,
        minifyFontValues: true,
        minifyGradients: true,
        reduceIdents: false,
        mergeRules: true,
        mergeLonghand: true,
      }],
    }),
  ].filter(Boolean),
};
