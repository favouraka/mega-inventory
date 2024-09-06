import * as esbuild from 'esbuild'

const isDev = process.argv.includes('--dev')

// Function to compile the code
async function compile(options) {
    const context = await esbuild.context(options)

    if (isDev) {
        await context.watch() // Watch for changes in the code
    } else {
        await context.rebuild() // Rebuild the code
        await context.dispose() // Dispose the context
    }
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`, // Define the NODE_ENV variable based on whether it's development or production
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: isDev ? 'inline' : false, // Generate inline sourcemaps in development mode
    sourcesContent: isDev,
    treeShaking: true,
    target: ['es2020'],
    minify: !isDev, // Minify the code in production mode
    plugins: [{
        name: 'watchPlugin',
        setup: function (build) {
            build.onStart(() => {
                console.log(`Build started at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
            })

            build.onEnd((result) => {
                if (result.errors.length > 0) {
                    console.log(`Build failed at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`, result.errors)
                } else {
                    console.log(`Build finished at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
                }
            })
        }
    }],
}

// Compile the code with specified options
compile({
    ...defaultOptions,
    entryPoints: ['./resources/js/components/pdf-component.js'], // Entry point of the code
    outfile: './resources/js/dist/components/pdf-component.js', // Output file path
})